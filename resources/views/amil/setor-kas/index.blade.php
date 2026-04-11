{{-- resources/views/amil/setor-kas/index.blade.php --}}
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
                        <p class="text-xs text-gray-500 mt-0.5">Kelola setoran kas dari amil ke lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        {{-- Tombol Filter --}}
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        {{-- Tombol Buat Setoran --}}
                        <a href="{{ route('amil.setor-kas.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Setoran
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Bar --}}
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $setorans->total() }}</span>
                        <span class="text-sm text-gray-500">Setoran</span>
                    </div>

                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Diterima:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $stats['total_diterima'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                            <span class="text-xs text-gray-500">Pending:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $stats['total_pending'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-red-500"></div>
                            <span class="text-xs text-gray-500">Ditolak:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $stats['total_ditolak'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs text-gray-500">Total Nominal:</span>
                            <span class="text-xs font-semibold text-gray-700">Rp
                                {{ number_format($stats['total_nominal'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request()->hasAny(['status', 'dari', 'sampai']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('amil.setor-kas.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                            <input type="date" name="dari" value="{{ request('dari') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                            <input type="date" name="sampai" value="{{ request('sampai') }}"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['status', 'dari', 'sampai']))
                            <a href="{{ route('amil.setor-kas.index') }}"
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

            {{-- Active Filter Tags --}}
            @if (request()->hasAny(['status', 'dari', 'sampai']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('dari'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Dari: {{ request('dari') }}
                                <button onclick="removeFilter('dari')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if (request('sampai'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Sampai: {{ request('sampai') }}
                                <button onclick="removeFilter('sampai')" class="hover:text-green-900 ml-1">×</button>
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
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">NO. SETORAN &amp; MASJID</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 w-28">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($setorans as $setor)
                                @php
                                    $bisaEdit  = $setor->bisa_diedit;
                                    $bisaHapus = $setor->bisa_dihapus;
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-green-50/20 transition-colors cursor-pointer expandable-row
                                    {{ $setor->status === 'pending' ? 'bg-yellow-50/30' : '' }}"
                                    data-target="detail-{{ $setor->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800">{{ $setor->no_setor }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $setor->tanggal_setor->format('d/m/Y') }}
                                            &middot;
                                            <span class="font-semibold text-gray-700">{{ $setor->jumlah_disetor_formatted }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-2 flex-wrap">
                                            {!! $setor->status_badge !!}
                                            <span class="text-xs text-gray-400">{{ $setor->masjid->nama ?? '-' }}</span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">

                                            {{-- Detail --}}
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('amil.setor-kas.show', $setor->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <div
                                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Detail
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            {{-- Edit --}}
                                            @if ($bisaEdit)
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('amil.setor-kas.edit', $setor->uuid) }}"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Edit
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Hapus --}}
                                            @if ($bisaHapus)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        onclick="openDeleteModal('{{ $setor->uuid }}', '{{ addslashes($setor->no_setor) }}')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Hapus
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

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
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Setoran —
                                                    {{ $setor->no_setor }}</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                {{-- Kolom 1: Data Setoran --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Data Setoran</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">No. Setoran</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $setor->no_setor }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal Setor</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $setor->tanggal_setor->format('d F Y') }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Masjid</p>
                                                            <p class="text-sm font-medium text-gray-800">{{ $setor->masjid->nama ?? '-' }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Periode & Nominal --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Periode &amp; Nominal</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Periode</p>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                {{ $setor->periode_dari->format('d F Y') }}
                                                                &mdash;
                                                                {{ $setor->periode_sampai->format('d F Y') }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-xs text-gray-400">Jumlah Disetor</p>
                                                            <p class="text-sm font-semibold text-green-600">{{ $setor->jumlah_disetor_formatted }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status --}}
                                                <div>
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                                                        Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Status</p>
                                                            <div class="mt-1">{!! $setor->status_badge !!}</div>
                                                        </div>
                                                        @if ($setor->amil)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Amil</p>
                                                                <p class="text-sm font-medium text-gray-800">{{ $setor->amil->nama_lengkap }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($setor->status === 'ditolak' && $setor->alasan_penolakan)
                                                            <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                                <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                                <p class="text-xs text-red-700">{{ $setor->alasan_penolakan }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
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
                            $bisaEdit  = $setor->bisa_diedit;
                            $bisaHapus = $setor->bisa_dihapus;
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile
                            {{ $setor->status === 'pending' ? 'bg-yellow-50/30' : '' }}"
                            data-target="detail-mobile-{{ $setor->uuid }}">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $setor->no_setor }}</h3>
                                        {!! $setor->status_badge !!}
                                    </div>
                                    <div class="flex items-center mt-1 flex-wrap gap-2">
                                        <span class="text-xs text-gray-500">{{ $setor->tanggal_setor->format('d/m/Y') }}</span>
                                        <span class="text-xs font-semibold text-gray-700">{{ $setor->jumlah_disetor_formatted }}</span>
                                        <span class="text-xs text-gray-400">{{ $setor->masjid->nama ?? '-' }}</span>
                                    </div>
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
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Data Setoran</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">No. Setoran:</span> {{ $setor->no_setor }}</p>
                                            <p><span class="text-gray-500">Tanggal:</span> {{ $setor->tanggal_setor->format('d F Y') }}</p>
                                            <p><span class="text-gray-500">Masjid:</span> {{ $setor->masjid->nama ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Periode &amp; Nominal</h4>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-gray-500">Periode:</span>
                                                {{ $setor->periode_dari->format('d F Y') }} &mdash; {{ $setor->periode_sampai->format('d F Y') }}
                                            </p>
                                            <p><span class="text-gray-500">Jumlah:</span>
                                                <span class="font-semibold text-green-600">{{ $setor->jumlah_disetor_formatted }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Status</h4>
                                        <div class="space-y-1">
                                            <div>{!! $setor->status_badge !!}</div>
                                            @if ($setor->amil)
                                                <p class="text-sm"><span class="text-gray-500">Amil:</span> {{ $setor->amil->nama_lengkap }}</p>
                                            @endif
                                            @if ($setor->status === 'ditolak' && $setor->alasan_penolakan)
                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                    <p class="text-xs text-red-600 font-medium">Alasan Ditolak:</p>
                                                    <p class="text-xs text-red-700">{{ $setor->alasan_penolakan }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Mobile Action Buttons --}}
                                    <div class="pt-2 flex items-center gap-2 flex-wrap">
                                        <a href="{{ route('amil.setor-kas.show', $setor->uuid) }}"
                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span class="text-xs ml-1">Detail</span>
                                        </a>

                                        @if ($bisaEdit)
                                            <a href="{{ route('amil.setor-kas.edit', $setor->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span class="text-xs ml-1">Edit</span>
                                            </a>
                                        @endif

                                        @if ($bisaHapus)
                                            <button type="button"
                                                onclick="openDeleteModal('{{ $setor->uuid }}', '{{ addslashes($setor->no_setor) }}')"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="text-xs ml-1">Hapus</span>
                                            </button>
                                        @endif
                                    </div>
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
                            class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>

                    @if (request()->hasAny(['status', 'dari', 'sampai']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('amil.setor-kas.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada setoran kas</p>
                        <a href="{{ route('amil.setor-kas.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat setoran sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- ── Modal: Hapus ── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[10000] flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Setoran</h3>
                <p class="text-sm text-gray-500 mb-1 text-center">
                    Hapus setoran "<span id="modal-delete-no" class="font-semibold text-gray-700"></span>"?
                </p>
                <p class="text-xs text-gray-400 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
                <form method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('delete-modal')"
                            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 rounded-xl text-sm font-medium text-white transition-all">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .rotate-90 { transform: rotate(90deg); }
        .rotate-180 { transform: rotate(180deg); }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Filter toggle ──
            const filterBtn = document.getElementById('filterButton');
            if (filterBtn) filterBtn.addEventListener('click', toggleFilter);

            // ── Desktop expandable rows ──
            document.querySelectorAll('.expandable-row').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon   = this.querySelector('.expand-icon');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-90');
                    }
                });
            });

            // ── Mobile expandable cards ──
            document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a, button')) return;
                    var target = document.getElementById(this.dataset.target);
                    var icon   = this.querySelector('.expand-icon-mobile');
                    if (target && icon) {
                        target.classList.toggle('hidden');
                        icon.classList.toggle('rotate-180');
                    }
                });
            });

            // ── Modal backdrop & ESC ──
            ['delete-modal'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) {
                    el.addEventListener('click', function (e) {
                        if (e.target === this) closeModal(id);
                    });
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeModal('delete-modal');
            });
        });

        // ── Modal helpers ──
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.closeModal = function (id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = '';
        };

        window.openDeleteModal = function (uuid, noSetor) {
            document.getElementById('modal-delete-no').textContent = noSetor;
            document.getElementById('delete-form').action = '/setor-kas/' + uuid;
            openModal('delete-modal');
        };

        // ── Filter helpers ──
        function toggleFilter() {
            var panel = document.getElementById('filter-panel');
            if (panel) panel.classList.toggle('hidden');
        }

        function removeFilter(filterName) {
            var url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endpush