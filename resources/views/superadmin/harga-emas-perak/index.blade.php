@extends('layouts.app')

@section('title', 'Harga Emas & Perak')

@section('content')
    <div class="space-y-5">
        <!-- Container utama -->
        <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden shadow-soft transition-all duration-300">

            <!-- Header + Button (tanpa search) -->
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-neutral-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-neutral-800">Harga Emas & Perak</h1>
                        <p class="text-xs sm:text-sm text-neutral-500 mt-0.5 sm:mt-1">Data referensi harga untuk perhitungan
                            nisab zakat</p>
                    </div>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Button Filter -->
                        <button type="button" id="filter-button"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-sm font-medium rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline">Filter</span>
                        </button>

                        <a href="{{ route('harga-emas-perak.create') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-soft hover:shadow-md active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="hidden sm:inline">Tambah Baru</span>
                            <span class="sm:hidden">Tambah</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - Hidden by default -->
            <div id="filter-panel" class="hidden px-4 sm:px-6 py-4 border-b border-neutral-200 bg-neutral-50/30">
                <form id="filter-form" method="GET" action="{{ route('harga-emas-perak.index') }}">
                    <div class="flex flex-wrap items-end gap-4">
                        <!-- Search Field -->
                        <div class="min-w-[200px] flex-1 sm:flex-none">
                            <label class="block text-xs font-medium text-neutral-600 mb-1.5">Cari Sumber</label>
                            <input type="text" id="filter-search" name="search" value="{{ request('search') }}"
                                placeholder="Cari sumber..."
                                class="pl-3 pr-4 py-2 w-full text-sm border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200">
                        </div>

                        <div class="min-w-[140px] flex-1 sm:flex-none">
                            <label class="block text-xs font-medium text-neutral-600 mb-1.5">Status</label>
                            <select id="filter-status" name="status"
                                class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                        <div class="min-w-[140px] flex-1 sm:flex-none">
                            <label class="block text-xs font-medium text-neutral-600 mb-1.5">Tanggal</label>
                            <input type="date" id="filter-tanggal" name="tanggal" value="{{ request('tanggal') }}"
                                class="pl-3 pr-3 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                        </div>
                        <div class="min-w-[140px] flex-1 sm:flex-none">
                            <label class="block text-xs font-medium text-neutral-600 mb-1.5">Sumber</label>
                            <select id="filter-sumber" name="sumber"
                                class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                                <option value="">Semua Sumber</option>
                                @foreach ($sumberList as $sumber)
                                    @if ($sumber)
                                        <option value="{{ $sumber }}"
                                            {{ request('sumber') == $sumber ? 'selected' : '' }}>
                                            {{ $sumber }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="min-w-[150px] flex-1 sm:flex-none">
                            <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urut Berdasarkan</label>
                            <select id="filter-sort-by" name="sort_by"
                                class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                                <option value="tanggal" {{ request('sort_by', 'tanggal') === 'tanggal' ? 'selected' : '' }}>
                                    Tanggal</option>
                                <option value="harga_emas_pergram"
                                    {{ request('sort_by') === 'harga_emas_pergram' ? 'selected' : '' }}>Harga Emas</option>
                                <option value="harga_perak_pergram"
                                    {{ request('sort_by') === 'harga_perak_pergram' ? 'selected' : '' }}>Harga Perak
                                </option>
                                <option value="sumber" {{ request('sort_by') === 'sumber' ? 'selected' : '' }}>Sumber
                                </option>
                                <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>
                                    Tanggal Input</option>
                            </select>
                        </div>
                        <div class="min-w-[130px] flex-1 sm:flex-none">
                            <label class="block text-xs font-medium text-neutral-600 mb-1.5">Urutan</label>
                            <select id="filter-sort-order" name="sort_order"
                                class="pl-3 pr-8 py-2 text-sm border border-neutral-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all duration-200 w-full">
                                <option value="desc" {{ request('sort_order', 'desc') === 'desc' ? 'selected' : '' }}>
                                    Menurun</option>
                                <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Menaik
                                </option>
                            </select>
                        </div>

                        <!-- Tombol Terapkan Filter -->
                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Terapkan
                            </button>

                            @if (request('search') ||
                                    request('status') ||
                                    request('tanggal') ||
                                    request('sumber') ||
                                    request('sort_by') ||
                                    request('sort_order'))
                                <a href="{{ route('harga-emas-perak.index') }}"
                                    class="inline-flex items-center gap-1 px-3 py-2 text-sm text-neutral-500 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Total + Active Filters -->
            <div class="px-4 sm:px-6 py-3 border-b border-neutral-100 bg-neutral-50/30">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-neutral-600">Total:</span>
                        <span class="text-sm font-semibold text-neutral-800">{{ $hargaEmasPerak->total() }}</span>
                        <span class="text-sm text-neutral-500">data harga</span>
                    </div>

                    <!-- Active Filters Badges -->
                    @if (request('search') ||
                            request('status') ||
                            request('tanggal') ||
                            request('sumber') ||
                            (request('sort_by') && request('sort_by') != 'tanggal') ||
                            (request('sort_order') && request('sort_order') != 'desc'))
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs text-neutral-400">Filter aktif:</span>

                            @if (request('search'))
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Pencarian: "{{ request('search') }}"
                                    <button onclick="removeFilter('search')"
                                        class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif

                            @if (request('status'))
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Status: {{ request('status') == 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                    <button onclick="removeFilter('status')"
                                        class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif

                            @if (request('tanggal'))
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Tgl: {{ \Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y') }}
                                    <button onclick="removeFilter('tanggal')"
                                        class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif

                            @if (request('sumber'))
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Sumber: {{ request('sumber') }}
                                    <button onclick="removeFilter('sumber')"
                                        class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif

                            @if (request('sort_by') && request('sort_by') != 'tanggal')
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Urutan: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                                    ({{ request('sort_order') == 'asc' ? 'Menaik' : 'Menurun' }})
                                    <button onclick="removeFilter('sort_by'); removeFilter('sort_order')"
                                        class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @elseif (request('sort_order') && request('sort_order') != 'desc')
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-full">
                                    Urutan: Menaik
                                    <button onclick="removeFilter('sort_order')"
                                        class="hover:text-primary-900 transition-colors">×</button>
                                </span>
                            @endif

                            <button onclick="resetAllFilters()"
                                class="text-xs text-neutral-500 hover:text-neutral-700 transition-colors">
                                Reset semua
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            @if ($hargaEmasPerak->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-neutral-200 bg-neutral-50">
                                <th class="px-4 py-4 text-center text-sm font-semibold text-neutral-700 w-10"></th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">TANGGAL & STATUS
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">HARGA EMAS</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">HARGA PERAK</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-neutral-700">SUMBER</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-neutral-700 w-20">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hargaEmasPerak as $harga)
                                <!-- Baris Utama -->
                                <tr class="border-b border-neutral-100 hover:bg-primary-50/20 transition-all duration-200 group cursor-pointer expandable-row"
                                    data-target="detail-{{ $harga->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-neutral-400 transform transition-transform duration-200 expand-icon inline-block"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div
                                            class="text-sm font-medium text-neutral-800 group-hover:text-primary-600 transition-colors duration-200">
                                            {{ $harga->tanggal->format('d/m/Y') }}
                                        </div>
                                        <div class="mt-1">
                                            @if ($harga->is_active)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-100">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-600">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-neutral-800">
                                            Rp {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}
                                        </span>
                                        <div class="text-xs text-neutral-400 mt-0.5">per gram</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-semibold text-neutral-800">
                                            Rp {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}
                                        </span>
                                        <div class="text-xs text-neutral-400 mt-0.5">per gram</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm {{ $harga->sumber ? 'text-neutral-800' : 'text-neutral-400' }}">
                                            {{ $harga->sumber ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <button type="button"
                                            class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                            data-uuid="{{ $harga->uuid }}"
                                            data-tanggal="{{ $harga->formatted_tanggal }}"
                                            data-active="{{ $harga->is_active ? '1' : '0' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Baris Expandable Desktop -->
                                <tr id="detail-{{ $harga->uuid }}"
                                    class="hidden border-b border-neutral-100 expandable-content">
                                    <td class="px-4 py-4 align-top bg-neutral-50/50"></td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50" colspan="4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <!-- Kolom 1: Informasi Dasar -->
                                            <div class="space-y-2">
                                                <h4
                                                    class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                                    Informasi Dasar</h4>
                                                <div class="text-xs text-neutral-500 space-y-0.5">
                                                    <div>Tanggal: <span
                                                            class="font-medium text-neutral-700">{{ $harga->tanggal->format('d F Y') }}</span>
                                                    </div>
                                                    <div>Sumber: <span
                                                            class="font-medium text-neutral-700">{{ $harga->sumber ?? 'Tidak ada sumber' }}</span>
                                                    </div>
                                                    <div>Status:
                                                        <span
                                                            class="font-medium {{ $harga->is_active ? 'text-green-600' : 'text-neutral-600' }}">
                                                            {{ $harga->is_active ? 'Aktif' : 'Nonaktif' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kolom 2: Keterangan -->
                                            <div>
                                                <h4
                                                    class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                                    Keterangan</h4>
                                                @if ($harga->keterangan)
                                                    <p class="text-sm text-neutral-600">{{ $harga->keterangan }}</p>
                                                @else
                                                    <p class="text-sm text-neutral-400 italic">Tidak ada keterangan</p>
                                                @endif
                                            </div>

                                            <!-- Kolom 3: Metadata -->
                                            <div class="text-xs text-neutral-400 space-y-0.5">
                                                <h4
                                                    class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                                    Riwayat</h4>
                                                <div>Dibuat: {{ $harga->created_at->format('d/m/Y H:i') }}</div>
                                                @if ($harga->updated_at != $harga->created_at)
                                                    <div>Diperbarui: {{ $harga->updated_at->format('d/m/Y H:i') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top bg-neutral-50/50 text-center">
                                        <div class="flex flex-col gap-2 items-center">
                                            <a href="{{ route('harga-emas-perak.edit', $harga->uuid) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('harga-emas-perak.toggle-status', $harga->uuid) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $harga->is_active ? 'bg-yellow-50 border-yellow-100 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 border-green-100 text-green-700 hover:bg-green-100' }} border text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        @if ($harga->is_active)
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        @endif
                                                    </svg>
                                                    {{ $harga->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ==================== MOBILE CARD VIEW ==================== -->
                <div class="block md:hidden divide-y divide-neutral-100">
                    @foreach ($hargaEmasPerak as $harga)
                        <div class="p-4 hover:bg-primary-50/20 transition-all duration-200">
                            <!-- Header Card (klik untuk expand) -->
                            <div class="expandable-row-mobile cursor-pointer"
                                data-target="detail-mobile-{{ $harga->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-neutral-400 transform transition-transform duration-200 expand-icon-mobile"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-neutral-400">Harga Emas & Perak</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-neutral-800">
                                            {{ $harga->tanggal->format('d/m/Y') }}
                                        </h3>

                                        <!-- Badge Status + Info Singkat -->
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            @if ($harga->is_active)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-100">
                                                    Aktif
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 bg-neutral-100 text-neutral-500 text-xs rounded-full">
                                                    Nonaktif
                                                </span>
                                            @endif
                                            <span class="text-xs text-neutral-500">Emas: Rp
                                                {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}</span>
                                            <span class="text-xs text-neutral-300">•</span>
                                            <span class="text-xs text-neutral-500">Perak: Rp
                                                {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <!-- Dropdown Button -->
                                    <div class="flex-shrink-0">
                                        <button type="button"
                                            class="dropdown-toggle p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all duration-200"
                                            data-uuid="{{ $harga->uuid }}"
                                            data-tanggal="{{ $harga->formatted_tanggal }}"
                                            data-active="{{ $harga->is_active ? '1' : '0' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $harga->uuid }}"
                                class="hidden mt-3 pt-3 border-t border-neutral-100">
                                <div class="space-y-4">
                                    <!-- Detail Harga -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                            Detail Harga</h4>
                                        <div class="bg-neutral-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Harga Emas</span>
                                                <span class="text-xs font-semibold text-neutral-800">Rp
                                                    {{ number_format($harga->harga_emas_pergram, 0, ',', '.') }}/gram</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Harga Perak</span>
                                                <span class="text-xs font-semibold text-neutral-800">Rp
                                                    {{ number_format($harga->harga_perak_pergram, 0, ',', '.') }}/gram</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-neutral-500">Sumber</span>
                                                <span
                                                    class="text-xs font-medium text-neutral-700">{{ $harga->sumber ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Keterangan -->
                                    @if ($harga->keterangan)
                                        <div>
                                            <h4
                                                class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-2">
                                                Keterangan</h4>
                                            <div class="bg-neutral-50 rounded-lg p-3">
                                                <p class="text-sm text-neutral-600">
                                                    {{ Str::limit($harga->keterangan, 100) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Riwayat -->
                                    <div class="pt-1 text-xs text-neutral-400 space-y-0.5 border-t border-neutral-200">
                                        <div>Dibuat: {{ $harga->created_at->format('d/m/Y H:i') }}</div>
                                        @if ($harga->updated_at != $harga->created_at)
                                            <div>Diperbarui: {{ $harga->updated_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </div>

                                    <!-- Tombol Aksi Mobile -->
                                    <div class="flex gap-2 pt-2">
                                        <a href="{{ route('harga-emas-perak.edit', $harga->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('harga-emas-perak.toggle-status', $harga->uuid) }}"
                                            method="POST" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 {{ $harga->is_active ? 'bg-yellow-50 border border-yellow-100 hover:bg-yellow-100 text-yellow-700' : 'bg-green-50 border border-green-100 hover:bg-green-100 text-green-700' }} text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    @if ($harga->is_active)
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    @endif
                                                </svg>
                                                {{ $harga->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <button type="button"
                                            onclick="showDeleteModal('{{ $harga->uuid }}', '{{ addslashes($harga->formatted_tanggal) }}')"
                                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-50 border border-red-100 hover:bg-red-100 text-red-700 text-xs font-medium rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($hargaEmasPerak->hasPages())
                    <div class="px-4 sm:px-6 py-3 border-t border-neutral-200 bg-neutral-50/30">
                        {{ $hargaEmasPerak->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="py-14 text-center animate-fade-in">
                    <div class="w-14 h-14 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if (request('search') || request('status') || request('tanggal') || request('sumber'))
                        <p class="text-sm text-neutral-500">Tidak ada hasil yang cocok dengan filter yang dipilih</p>
                        <a href="{{ route('harga-emas-perak.index') }}"
                            class="inline-block mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Reset
                            filter</a>
                    @else
                        <p class="text-sm text-neutral-500">Belum ada data harga emas & perak</p>
                        <a href="{{ route('harga-emas-perak.create') }}"
                            class="inline-block mt-3 text-sm text-primary-600 hover:text-primary-700 transition-colors">Tambah
                            data</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu -->
    <div id="dropdown-container"
        class="fixed hidden z-50 bg-white rounded-lg shadow-lg border border-neutral-200 min-w-[140px]">
        <div class="py-1">
            <a href="#" id="dropdown-edit-link"
                class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <button type="button" id="dropdown-delete-btn"
                class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Hapus
            </button>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black/30 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-sm w-full">
            <div class="p-5">
                <div class="flex justify-center mb-4">
                    <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 mb-2 text-center">Hapus Data Harga</h3>
                <p class="text-sm text-neutral-500 mb-1 text-center">
                    Yakin ingin menghapus data harga tanggal "<span id="modal-tanggal"
                        class="font-semibold text-neutral-700"></span>"?
                </p>
                <p class="text-xs text-neutral-400 mb-5 text-center">Tindakan ini tidak dapat dibatalkan.</p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-3 py-2 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-colors">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-sm font-medium text-white transition-colors">
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
        let currentDropdownData = null;
        const editBaseUrl = "{{ rtrim(route('harga-emas-perak.index'), '/') }}";

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const deleteForm = document.getElementById('delete-form');
            const filterButton = document.getElementById('filter-button');
            const filterPanel = document.getElementById('filter-panel');

            // Toggle filter panel
            if (filterButton && filterPanel) {
                filterButton.addEventListener('click', function() {
                    filterPanel.classList.toggle('hidden');
                });
            }

            // Desktop Expandable row
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.dropdown-toggle') || e.target.closest('a') || e.target
                        .closest('button[type="submit"]')) return;
                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon');
                    if (targetRow) {
                        targetRow.classList.toggle('hidden');
                        if (icon) icon.classList.toggle('rotate-90');
                    }
                });
            });

            // Mobile Expandable Cards
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.dropdown-toggle') || e.target.closest('a') || e.target
                        .closest('button[type="submit"]')) return;
                    const targetId = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon-mobile');
                    if (targetContent) {
                        targetContent.classList.toggle('hidden');
                        if (icon) icon.classList.toggle('rotate-180');
                    }
                });
            });

            // Dropdown
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');
                if (toggle) {
                    e.stopPropagation();
                    const dropdownUuid = toggle.getAttribute('data-uuid');
                    const tanggal = toggle.getAttribute('data-tanggal');

                    if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid && !
                        dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);
                    const rect = toggle.getBoundingClientRect();

                    dropdownContainer.style.visibility = 'hidden';
                    dropdownContainer.classList.remove('hidden');

                    requestAnimationFrame(() => {
                        const dropdownWidth = dropdownContainer.offsetWidth;
                        const dropdownHeight = dropdownContainer.offsetHeight;
                        let top = rect.bottom + 6;
                        let left = rect.right - dropdownWidth;
                        if (left < 10) left = 10;
                        if (left + dropdownWidth > window.innerWidth - 10) left = window
                            .innerWidth - dropdownWidth - 10;
                        if (rect.bottom + dropdownHeight > window.innerHeight) top = rect.top -
                            dropdownHeight - 6;
                        if (top < 6) top = 6;
                        dropdownContainer.style.top = top + 'px';
                        dropdownContainer.style.left = left + 'px';
                        dropdownContainer.style.visibility = '';
                    });

                    currentDropdownData = {
                        uuid: dropdownUuid,
                        tanggal: tanggal
                    };
                    editLink.href = `${editBaseUrl}/${dropdownUuid}/edit`;
                } else if (!dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });

            // Delete handler dari dropdown
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!currentDropdownData?.uuid) return;
                dropdownContainer.classList.add('hidden');
                showDeleteModal(currentDropdownData.uuid, currentDropdownData.tanggal);
            });

            // Modal handlers
            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            window.addEventListener('scroll', () => dropdownContainer.classList.add('hidden'), true);
            window.addEventListener('resize', () => dropdownContainer.classList.add('hidden'));
        });

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');

            if (filterName === 'search') {
                const filterSearch = document.getElementById('filter-search');
                if (filterSearch) filterSearch.value = '';
            }

            // Jika menghapus sort_by, hapus juga sort_order
            if (filterName === 'sort_by') {
                url.searchParams.delete('sort_order');
            }

            window.location.href = url.toString();
        }

        function resetAllFilters() {
            const url = new URL(window.location.href);
            url.searchParams.delete('search');
            url.searchParams.delete('status');
            url.searchParams.delete('tanggal');
            url.searchParams.delete('sumber');
            url.searchParams.delete('sort_by');
            url.searchParams.delete('sort_order');
            url.searchParams.set('page', '1');

            // Reset input values
            const filterSearch = document.getElementById('filter-search');
            const filterStatus = document.getElementById('filter-status');
            const filterTanggal = document.getElementById('filter-tanggal');
            const filterSumber = document.getElementById('filter-sumber');
            const filterSortBy = document.getElementById('filter-sort-by');
            const filterSortOrder = document.getElementById('filter-sort-order');

            if (filterSearch) filterSearch.value = '';
            if (filterStatus) filterStatus.value = '';
            if (filterTanggal) filterTanggal.value = '';
            if (filterSumber) filterSumber.value = '';
            if (filterSortBy) filterSortBy.value = 'tanggal';
            if (filterSortOrder) filterSortOrder.value = 'desc';

            window.location.href = url.toString();
        }

        function showDeleteModal(uuid, tanggal) {
            document.getElementById('modal-tanggal').textContent = tanggal;
            document.getElementById('delete-form').action = `/harga-emas-perak/${uuid}`;
            document.getElementById('delete-modal').classList.remove('hidden');
        }
    </script>
@endpush
