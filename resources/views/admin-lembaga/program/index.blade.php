{{-- resources/views/admin-masjid/program/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Program Zakat')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">Program Zakat</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola program zakat dan penyaluran dana</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah -->
                        <a href="{{ route('program-zakat.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Program
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-6 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $programs->total() }}</span>
                        <span class="text-sm text-gray-500">Program</span>
                    </div>

                    <!-- Active Filters Tags -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if (request('q'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ ucfirst(request('status')) }}
                                <button onclick="removeFilter('status')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif

                        @if (request('tahun'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Tahun: {{ request('tahun') }}
                                <button onclick="removeFilter('tahun')"
                                    class="hover:text-green-900 transition-colors ml-1 text-lg leading-none">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="px-6 py-4 border-b border-gray-100 bg-green-50/30 hidden">
                <form method="GET" action="{{ route('program-zakat.index') }}">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Search Field -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Program</label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                placeholder="Cari nama program, kode..."
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                        </div>

                        <!-- Filter Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Filter Tahun -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                            <select name="tahun"
                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Tahun</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}"
                                        {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol di ujung kanan -->
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            Terapkan
                        </button>
                        <button type="button" id="closeFilterPanelBtn"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Tutup
                        </button>
                        @if (request('q') || request('status') || request('tahun'))
                            <a href="{{ route('program-zakat.index') }}"
                                class="px-4 py-2 text-gray-500 hover:text-red-600 text-sm font-medium transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($programs->count() > 0)
                <!-- Tabel Desktop -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    NO</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    PROGRAM</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    PERIODE</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    TARGET</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    PROGRESS</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    STATUS</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                                    AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($programs as $index => $program)
                                @php
                                    $canEdit = !in_array($program->status, ['selesai', 'dibatalkan']);
                                    $canDelete = $program->status === 'draft';
                                    $statusColor = match ($program->status) {
                                        'draft' => 'bg-gray-100 text-gray-700',
                                        'aktif' => 'bg-green-100 text-green-700',
                                        'selesai' => 'bg-blue-100 text-blue-700',
                                        'dibatalkan' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                    $statusLabel = match ($program->status) {
                                        'draft' => 'Draft',
                                        'aktif' => 'Aktif',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => ucfirst($program->status),
                                    };
                                @endphp
                                <tr
                                    class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300">
                                    <td class="px-6 py-3">
                                        <span
                                            class="text-sm font-medium text-gray-800">{{ $programs->firstItem() + $index }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div>
                                            <div
                                                class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors">
                                                {{ $program->nama_program }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $program->kode_program }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($program->tanggal_mulai)->format('d M Y') }}
                                            @if ($program->tanggal_selesai)
                                                <span class="text-xs text-gray-400">-
                                                    {{ \Carbon\Carbon::parse($program->tanggal_selesai)->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        @if ($program->target_dana)
                                            <div class="text-sm font-medium text-gray-700">
                                                Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                            </div>
                                        @endif
                                        @if ($program->target_mustahik)
                                            <div class="text-xs text-gray-500 mt-0.5">
                                                {{ $program->target_mustahik }} Mustahik
                                            </div>
                                        @endif
                                        @if (!$program->target_dana && !$program->target_mustahik)
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="space-y-1.5 min-w-[130px]">
                                            @if ($program->target_dana)
                                                <div>
                                                    <div class="flex items-center justify-between text-xs mb-0.5">
                                                        <span class="text-gray-600">Dana</span>
                                                        <span
                                                            class="font-medium text-green-600">{{ $program->progress_dana }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-green-600 h-1.5 rounded-full"
                                                            style="width: {{ $program->progress_dana }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($program->target_mustahik)
                                                <div>
                                                    <div class="flex items-center justify-between text-xs mb-0.5">
                                                        <span class="text-gray-600">Mustahik</span>
                                                        <span
                                                            class="font-medium text-blue-600">{{ $program->progress_mustahik }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                        <div class="bg-blue-600 h-1.5 rounded-full"
                                                            style="width: {{ $program->progress_mustahik }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <!-- Ikon Detail -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('program-zakat.show', $program->uuid) }}"
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

                                            <!-- Ikon Edit -->
                                            @if ($canEdit)
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('program-zakat.edit', $program->uuid) }}"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Edit
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Ikon Hapus -->
                                            @if ($canDelete)
                                                <div class="relative group/tooltip">
                                                    <button type="button"
                                                        class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                        data-uuid="{{ $program->uuid }}"
                                                        data-nama="{{ $program->nama_program }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                    <div
                                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Hapus
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($programs as $index => $program)
                        @php
                            $canEdit = !in_array($program->status, ['selesai', 'dibatalkan']);
                            $canDelete = $program->status === 'draft';
                            $statusColor = match ($program->status) {
                                'draft' => 'bg-gray-100 text-gray-700',
                                'aktif' => 'bg-green-100 text-green-700',
                                'selesai' => 'bg-blue-100 text-blue-700',
                                'dibatalkan' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                            $statusLabel = match ($program->status) {
                                'draft' => 'Draft',
                                'aktif' => 'Aktif',
                                'selesai' => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                                default => ucfirst($program->status),
                            };
                        @endphp
                        <div
                            class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span
                                            class="text-xs font-medium text-gray-500">#{{ $programs->firstItem() + $index }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-800 mb-1">
                                        {{ $program->nama_program }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $program->kode_program }}</p>

                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($program->tanggal_mulai)->format('d M Y') }}
                                            @if ($program->tanggal_selesai)
                                                - {{ \Carbon\Carbon::parse($program->tanggal_selesai)->format('d M Y') }}
                                            @endif
                                        </div>

                                        @if ($program->target_dana)
                                            <div class="space-y-1">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-600">
                                                        Dana: Rp {{ number_format($program->realisasi_dana, 0, ',', '.') }}
                                                        / Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                                    </span>
                                                    <span class="font-medium text-green-600">
                                                        {{ $program->progress_dana }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-green-600 h-1.5 rounded-full"
                                                        style="width: {{ $program->progress_dana }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($program->target_mustahik)
                                            <div class="space-y-1">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-600">
                                                        Mustahik: {{ $program->realisasi_mustahik }}
                                                        / {{ $program->target_mustahik }}
                                                    </span>
                                                    <span class="font-medium text-blue-600">
                                                        {{ $program->progress_mustahik }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-blue-600 h-1.5 rounded-full"
                                                        style="width: {{ $program->progress_mustahik }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <!-- Detail -->
                                    <div class="relative group/tooltip">
                                        <a href="{{ route('program-zakat.show', $program->uuid) }}"
                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <div
                                            class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                            Detail
                                        </div>
                                    </div>

                                    <!-- Edit -->
                                    @if ($canEdit)
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('program-zakat.edit', $program->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Edit
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Hapus -->
                                    @if ($canDelete)
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $program->uuid }}"
                                                data-nama="{{ $program->nama_program }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <div
                                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Hapus
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($programs->hasPages())
                    <div class="px-6 py-3 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $programs->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
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

                    @if (request('q') || request('status') || request('tahun'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('program-zakat.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada program zakat</p>
                        <a href="{{ route('program-zakat.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Buat program sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div
            class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Program</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus program "<span id="modal-program-name"
                        class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('delete-modal');
            const deleteForm = document.getElementById('delete-form');
            const cancelDeleteBtn = document.getElementById('cancel-delete-btn');

            // Filter Panel elements
            const filterButton = document.getElementById('filterButton');
            const filterPanel = document.getElementById('filterPanel');
            const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

            // Toggle filter panel
            if (filterButton && filterPanel) {
                filterButton.addEventListener('click', function() {
                    if (filterPanel.classList.contains('hidden')) {
                        filterPanel.classList.remove('hidden');
                    } else {
                        filterPanel.classList.add('hidden');
                    }
                });
            }

            // Tutup filter panel
            if (closeFilterPanelBtn) {
                closeFilterPanelBtn.addEventListener('click', function() {
                    filterPanel.classList.add('hidden');
                });
            }

            // Delete button handler
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const uuid = this.getAttribute('data-uuid');
                    const nama = this.getAttribute('data-nama');

                    document.getElementById('modal-program-name').textContent = nama;
                    deleteForm.action = `/program-zakat/${uuid}`;
                    deleteModal.classList.remove('hidden');
                });
            });

            // Cancel delete
            if (cancelDeleteBtn) {
                cancelDeleteBtn.addEventListener('click', function() {
                    deleteModal.classList.add('hidden');
                });
            }

            // Close modal when clicking outside
            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.add('hidden');
                }
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
