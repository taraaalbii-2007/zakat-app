@extends('layouts.app')

@section('title', 'Data Amil Semua Lembaga')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Data Amil Semua Lembaga</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi data amil dari seluruh lembaga</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'status', 'lembaga_id']) ? 'bg-green-50' : '' }}">
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
                        <span class="text-sm font-semibold text-gray-800">{{ $totalAmil }}</span>
                        <span class="text-sm text-gray-500">Amil dari</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $lembagas->count() }}</span>
                        <span class="text-sm text-gray-500">Lembaga</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel"
                class="{{ request()->hasAny(['q', 'status', 'lembaga_id']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.amil.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Lembaga</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari nama lembaga..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Status -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status Amil</label>
                                <select name="status"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>
                                        Nonaktif</option>
                                </select>
                            </div>

                            <!-- Filter Lembaga -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Lembaga</label>
                                <select name="lembaga_id"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Lembaga</option>
                                    @foreach ($lembagas as $lembaga)
                                        <option value="{{ $lembaga->id }}"
                                            {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                            {{ $lembaga->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'lembaga_id']))
                            <a href="{{ route('superadmin.amil.index') }}"
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
            @if (request()->hasAny(['q', 'status', 'lembaga_id']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>

                        @if (request('q'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                "{{ request('q') }}"
                                <button onclick="removeFilter('q')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif

                        @if (request('status'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status:
                                {{ request('status') == 'aktif' ? 'Aktif' : (request('status') == 'cuti' ? 'Cuti' : 'Nonaktif') }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif

                        @if (request('lembaga_id'))
                            <div
                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Lembaga:
                                {{ $lembagas->firstWhere('id', request('lembaga_id'))?->nama ?? request('lembaga_id') }}
                                <button onclick="removeFilter('lembaga_id')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif


            <!-- Tabel dengan Expandable Row -->
            @if ($lembagas->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th
                                    class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    LEMBAGA</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">
                                    ALAMAT</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    JUMLAH AMIL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lembagas as $lembaga)
                                <!-- Baris Utama (Expandable) -->
                                <tr class="border-b border-gray-100 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $lembaga->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <span
                                                class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                {{ $lembaga->nama }}
                                            </span>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat amil</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 hidden lg:table-cell">
                                        <span
                                            class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200">
                                            {{ $lembaga->amils->count() }} Amil
                                        </span>
                                    </td>
                                </tr>

                                <!-- Baris Expandable (Detail Amil) -->
                                <tr id="detail-{{ $lembaga->id }}"
                                    class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-gray-50/30"></td>
                                    <td class="px-6 py-4 align-top bg-gray-50/30" colspan="3">
                                        <div class="space-y-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">
                                                    Daftar Amil — {{ $lembaga->nama }}
                                                </h3>
                                            </div>

                                            @if ($lembaga->amils->isEmpty())
                                                <div
                                                    class="text-center py-8 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                    Belum ada data amil untuk lembaga ini
                                                </div>
                                            @else
                                                <div class="overflow-x-auto rounded-xl border border-gray-200">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th
                                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                    NAMA</th>
                                                                <th
                                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                                                    KONTAK</th>
                                                                <th
                                                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                                                    STATUS</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-100">
                                                            @foreach ($lembaga->amils as $amil)
                                                                @php
                                                                    $hasFoto =
                                                                        $amil->foto &&
                                                                        Storage::disk('public')->exists($amil->foto);
                                                                    $initial = strtoupper(
                                                                        substr($amil->nama_lengkap, 0, 1),
                                                                    );
                                                                    $avatarColors = [
                                                                        'ABCD' => 'bg-green-500',
                                                                        'EFGH' => 'bg-blue-500',
                                                                        'IJKL' => 'bg-purple-500',
                                                                        'MNOP' => 'bg-orange-500',
                                                                        'QRST' => 'bg-red-500',
                                                                        'UVWX' => 'bg-teal-500',
                                                                    ];
                                                                    $avatarBg = 'bg-gray-500';
                                                                    foreach ($avatarColors as $letters => $color) {
                                                                        if (in_array($initial, str_split($letters))) {
                                                                            $avatarBg = $color;
                                                                            break;
                                                                        }
                                                                    }
                                                                    $statusMap = [
                                                                        'aktif' => [
                                                                            'dot' => 'bg-green-500',
                                                                            'bg' => 'bg-green-100 text-green-800',
                                                                            'label' => 'Aktif',
                                                                        ],
                                                                        'cuti' => [
                                                                            'dot' => 'bg-yellow-500',
                                                                            'bg' => 'bg-yellow-100 text-yellow-800',
                                                                            'label' => 'Cuti',
                                                                        ],
                                                                        'nonaktif' => [
                                                                            'dot' => 'bg-red-500',
                                                                            'bg' => 'bg-red-100 text-red-800',
                                                                            'label' => 'Nonaktif',
                                                                        ],
                                                                    ];
                                                                    $status =
                                                                        $statusMap[$amil->status] ??
                                                                        $statusMap['nonaktif'];
                                                                @endphp
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td class="px-4 py-3">
                                                                        <div class="flex items-center gap-3">
                                                                            @if ($hasFoto)
                                                                                <img class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0"
                                                                                    src="{{ Storage::url($amil->foto) }}"
                                                                                    alt="{{ $amil->nama_lengkap }}">
                                                                            @else
                                                                                <div
                                                                                    class="h-8 w-8 rounded-full flex-shrink-0 flex items-center justify-center ring-2 ring-gray-100 {{ $avatarBg }}">
                                                                                    <span
                                                                                        class="text-xs font-semibold text-white">{{ $initial }}</span>
                                                                                </div>
                                                                            @endif
                                                                            <div>
                                                                                <div class="flex items-center gap-1.5">
                                                                                    <span
                                                                                        class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</span>
                                                                                    <span
                                                                                        class="px-1.5 py-0.5 rounded-full text-xs font-medium {{ $amil->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                                                                        {{ $amil->jenis_kelamin == 'L' ? 'L' : 'P' }}
                                                                                    </span>
                                                                                </div>
                                                                                <div class="text-xs text-gray-400">
                                                                                    {{ $amil->kode_amil }}</div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-4 py-3 hidden sm:table-cell">
                                                                        <div class="text-sm text-gray-700">
                                                                            {{ $amil->telepon ?? '-' }}</div>
                                                                        <div class="text-xs text-gray-400">
                                                                            {{ Str::limit($amil->email ?? '-', 25) }}</div>
                                                                    </td>
                                                                    <td class="px-4 py-3">
                                                                        <span
                                                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $status['bg'] }}">
                                                                            <span
                                                                                class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }} mr-1"></span>
                                                                            {{ $status['label'] }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW ==================== -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($lembagas as $lembaga)
                        <div
                            class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200">
                            <!-- Header Card (klik untuk expand) -->
                            <div class="expandable-row-mobile cursor-pointer"
                                data-target="detail-mobile-{{ $lembaga->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Lembaga</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words pr-2">
                                            {{ $lembaga->nama }}
                                        </h3>

                                        <!-- Badge Jumlah Amil -->
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                                                {{ $lembaga->amils->count() }} Amil
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Chevron -->
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $lembaga->id }}"
                                class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    @if ($lembaga->alamat)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                                Alamat</h4>
                                            <p class="text-sm text-gray-600">{{ $lembaga->alamat }}</p>
                                        </div>
                                    @endif

                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                            Daftar Amil</h4>
                                        @if ($lembaga->amils->isEmpty())
                                            <p class="text-sm text-gray-400 italic">Belum ada data amil</p>
                                        @else
                                            <div class="space-y-3">
                                                @foreach ($lembaga->amils as $amil)
                                                    @php
                                                        $hasFoto =
                                                            $amil->foto && Storage::disk('public')->exists($amil->foto);
                                                        $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                                                        $statusMap = [
                                                            'aktif' => [
                                                                'dot' => 'bg-green-500',
                                                                'bg' => 'bg-green-100 text-green-800',
                                                                'label' => 'Aktif',
                                                            ],
                                                            'cuti' => [
                                                                'dot' => 'bg-yellow-500',
                                                                'bg' => 'bg-yellow-100 text-yellow-800',
                                                                'label' => 'Cuti',
                                                            ],
                                                            'nonaktif' => [
                                                                'dot' => 'bg-red-500',
                                                                'bg' => 'bg-red-100 text-red-800',
                                                                'label' => 'Nonaktif',
                                                            ],
                                                        ];
                                                        $status = $statusMap[$amil->status] ?? $statusMap['nonaktif'];
                                                    @endphp
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <div class="flex items-center gap-3">
                                                            @if ($hasFoto)
                                                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200 flex-shrink-0"
                                                                    src="{{ Storage::url($amil->foto) }}"
                                                                    alt="{{ $amil->nama_lengkap }}">
                                                            @else
                                                                <div
                                                                    class="h-10 w-10 rounded-full flex-shrink-0 flex items-center justify-center bg-green-100">
                                                                    <span
                                                                        class="text-sm font-semibold text-green-600">{{ $initial }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1 min-w-0">
                                                                <div class="flex items-center justify-between">
                                                                    <p class="text-sm font-medium text-gray-900">
                                                                        {{ $amil->nama_lengkap }}</p>
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $status['bg'] }}">
                                                                        <span
                                                                            class="w-1.5 h-1.5 rounded-full {{ $status['dot'] }} mr-1"></span>
                                                                        {{ $status['label'] }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-xs text-gray-500">{{ $amil->kode_amil }}
                                                                </p>
                                                                @if ($amil->telepon)
                                                                    <p class="text-xs text-gray-400 mt-1">
                                                                        {{ $amil->telepon }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>

                    @if (request('q') || request('status') || request('lembaga_id'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('superadmin.amil.index') }}"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset semua filter
                        </a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada data lembaga</p>
                        <a href="{{ route('lembaga.create') }}"
                            class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah lembaga sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .rotate-90 {
            transform: rotate(90deg);
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        @keyframes fade-in {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scale-in {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fade-in 0.2s ease-out;
        }

        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter Panel elements
            const filterButton = document.getElementById('filterButton');
            const filterPanel = document.getElementById('filterPanel');
            const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

            // Toggle filter panel
            if (filterButton && filterPanel) {
                filterButton.addEventListener('click', function() {
                    filterPanel.classList.toggle('hidden');
                });
            }

            // Tutup filter panel
            if (closeFilterPanelBtn) {
                closeFilterPanelBtn.addEventListener('click', function() {
                    filterPanel.classList.add('hidden');
                });
            }

            // Desktop Expandable row
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon');

                    if (targetRow) {
                        targetRow.classList.toggle('hidden');
                        if (icon) {
                            icon.classList.toggle('rotate-90');
                        }
                    }
                });
            });

            // Mobile Expandable Cards
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon-mobile');
                    const chevron = this.querySelector('.expand-icon-mobile-chevron');

                    if (targetContent) {
                        targetContent.classList.toggle('hidden');
                        if (icon) {
                            icon.classList.toggle('rotate-90');
                        }
                        if (chevron) {
                            chevron.classList.toggle('rotate-90');
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
