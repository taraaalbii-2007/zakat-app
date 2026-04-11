{{-- resources/views/admin-lembaga/setor-kas/riwayat.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Setoran Kas')

@section('content')
    <div class="space-y-6">

        {{-- ── Main Card ── --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Riwayat Setoran Kas</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan pantau setoran kas dari para amil</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol ke Pending -->
                        <a href="{{ route('admin-lembaga.setor-kas.pending') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-200 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Pending
                            @if (isset($pendingCount) && $pendingCount > 0)
                                <span
                                    class="ml-1.5 px-1.5 py-0.5 bg-primary-600 text-white text-xs rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </a>

                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-primary-500 hover:bg-primary-50 text-primary-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>
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
                        @php
                            $statDiterima = $summary['diterima'] ?? null;
                            $statDitolak = $summary['ditolak'] ?? null;
                            $statPending = $summary['pending'] ?? null;
                        @endphp
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
                            <div class="w-2 h-2 rounded-full bg-primary-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span
                                class="text-xs font-semibold text-gray-700">{{ number_format($statPending->total ?? 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal:</span>
                            <span class="text-xs font-semibold text-gray-700">
                                Rp
                                {{ number_format(($statDiterima->jumlah ?? 0) + ($statDitolak->jumlah ?? 0) + ($statPending->jumlah ?? 0), 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['q', 'status', 'dari', 'sampai']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-primary-50/30">
                <form method="GET" action="{{ route('admin-lembaga.setor-kas.riwayat') }}" id="filter-form">
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
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima
                                </option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai</label>
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'dari', 'sampai']))
                            <a href="{{ route('admin-lembaga.setor-kas.riwayat') }}"
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
            @if (request()->hasAny(['q', 'status', 'dari', 'sampai']))
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
                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('dari'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Dari: {{ request('dari') }}
                                <button onclick="removeFilter('dari')" class="hover:text-primary-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('sampai'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-primary-50 text-primary-700 text-xs rounded-lg border border-primary-200">
                                Sampai: {{ request('sampai') }}
                                <button onclick="removeFilter('sampai')" class="hover:text-primary-900 ml-1">×</button>
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
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 w-12">NO</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">AMIL &amp; SETORAN</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">PERIODE</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">JUMLAH</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">SELISIH</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">STATUS</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($setorans as $index => $setor)
                                @php
                                    $amilNama = $setor->amil->nama_lengkap ?? ($setor->amil->pengguna->username ?? '-');
                                    $statusBadge = match ($setor->status) {
                                        'diterima'
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800 border border-green-200">Diterima</span>',
                                        'ditolak'
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800 border border-red-200">Ditolak</span>',
                                        default
                                            => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-800 border border-primary-200">Pending</span>',
                                    };
                                    $selisih = $setor->jumlah_dihitung_fisik
                                        ? $setor->jumlah_disetor - $setor->jumlah_dihitung_fisik
                                        : null;
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-4 text-center text-sm text-gray-500">
                                        {{ $setorans->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800">{{ $amilNama }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            <span class="font-mono">{{ $setor->no_setor }}</span>
                                            &middot; {{ $setor->tanggal_setor->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $setor->periode_dari->format('d M Y') }} <br>
                                        <span class="text-xs text-gray-400">s/d</span>
                                        {{ $setor->periode_sampai->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span
                                            class="text-sm font-semibold text-gray-800">{{ $setor->jumlah_disetor_formatted }}</span>
                                        @if ($setor->jumlah_dihitung_fisik)
                                            <div class="text-xs text-gray-400">Fisik:
                                                {{ number_format($setor->jumlah_dihitung_fisik, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if ($selisih !== null)
                                            <span
                                                class="text-sm {{ $selisih == 0 ? 'text-green-600' : ($selisih > 0 ? 'text-red-600' : 'text-blue-600') }}">
                                                {{ $selisih > 0 ? '+' : '' }}{{ number_format($selisih, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            {!! $statusBadge !!}
                                            @if ($setor->status == 'diterima' && $setor->diterima_at)
                                                <div class="text-xs text-gray-400">
                                                    {{ $setor->diterima_at->format('d M Y, H:i') }}
                                                </div>
                                            @endif
                                            @if ($setor->status == 'ditolak' && $setor->ditolak_at)
                                                <div class="text-xs text-gray-400">
                                                    {{ $setor->ditolak_at->format('d M Y, H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
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
                                                </div>
                                            </div>
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
                            $statusBadge = match ($setor->status) {
                                'diterima'
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Diterima</span>',
                                'ditolak'
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-800">Ditolak</span>',
                                default
                                    => '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-primary-100 text-primary-800">Pending</span>',
                            };
                            $selisih = $setor->jumlah_dihitung_fisik
                                ? $setor->jumlah_disetor - $setor->jumlah_dihitung_fisik
                                : null;
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $amilNama }}</h3>
                                        {!! $statusBadge !!}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="font-mono">{{ $setor->no_setor }}</span>
                                        &middot; {{ $setor->tanggal_setor->format('d M Y') }}
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 mt-3 text-sm">
                                        <div>
                                            <span class="text-xs text-gray-400">Periode</span>
                                            <p class="text-xs text-gray-700">
                                                {{ $setor->periode_dari->format('d M Y') }} <br>
                                                → {{ $setor->periode_sampai->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="text-xs text-gray-400">Jumlah</span>
                                            <p class="text-sm font-semibold text-gray-800">
                                                {{ $setor->jumlah_disetor_formatted }}</p>
                                            @if ($setor->jumlah_dihitung_fisik)
                                                <p class="text-xs text-gray-400">Fisik:
                                                    {{ number_format($setor->jumlah_dihitung_fisik, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($selisih !== null)
                                        <div class="mt-2">
                                            <span class="text-xs text-gray-400">Selisih:</span>
                                            <span
                                                class="text-xs {{ $selisih == 0 ? 'text-green-600' : ($selisih > 0 ? 'text-red-600' : 'text-blue-600') }}">
                                                {{ $selisih > 0 ? '+' : '' }}{{ number_format($selisih, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($setor->status == 'diterima' && $setor->diterima_at)
                                        <div class="mt-2 text-xs text-gray-400">
                                            Diterima: {{ $setor->diterima_at->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                    @if ($setor->status == 'ditolak' && $setor->ditolak_at)
                                        <div class="mt-2 text-xs text-gray-400">
                                            Ditolak: {{ $setor->ditolak_at->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('admin-lembaga.setor-kas.show', $setor->uuid) }}"
                                    class="flex-shrink-0 p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
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
                            class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    @if (request()->hasAny(['q', 'status', 'dari', 'sampai']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('admin-lembaga.setor-kas.riwayat') }}"
                            class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data setoran kas</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter toggle
            const filterBtn = document.getElementById('filterButton');
            if (filterBtn) {
                filterBtn.addEventListener('click', toggleFilter);
            }
        });

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
    </script>
@endpush
