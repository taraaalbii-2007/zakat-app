@extends('layouts.app')

@section('title', 'Kelola Data Lembaga')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Lembaga</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $lembagas->total() }} Lembaga</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                        {{-- Tambah --}}
                        <a href="{{ route('lembaga.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Tambah
                            </span>
                        </a>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                            {{ request()->hasAny(['provinsi_kode', 'status']) ? 'ring-2 ring-primary' : '' }}">
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
                                <span id="search-button-text"
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Cari
                                </span>
                            </button>
                            <form method="GET" action="{{ route('lembaga.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @foreach (['provinsi_kode', 'status'] as $filter)
                                    @if (request($filter))
                                        <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                    @endif
                                @endforeach
                                <div class="flex items-center gap-2">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari lembaga..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                    @if (request()->hasAny(['q', 'provinsi_kode', 'kota_kode', 'status']))
                                        <a href="{{ route('lembaga.index') }}"
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
                class="{{ request()->hasAny(['provinsi_kode', 'status']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('lembaga.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">

                        @if(isset($provinces) && $provinces->count() > 0)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Provinsi</label>
                            <select name="provinsi_kode"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Provinsi</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->code }}" {{ request('provinsi_kode') == $province->code ? 'selected' : '' }}>
                                        {{ $province->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>

                    </div>

                    @if (request()->hasAny(['provinsi_kode', 'status']))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('lembaga.index', request('q') ? ['q' => request('q')] : []) }}"
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

            @if ($lembagas->count() > 0)
                {{-- Desktop View --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Lembaga
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($lembagas as $lembaga)
                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
                                    data-target="detail-{{ $lembaga->uuid }}">
                                    <td class="px-4 py-4">
                                        <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $lembaga->nama }}</div>
                                                <div class="text-xs text-gray-500">Klik untuk melihat detail</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                data-uuid="{{ $lembaga->uuid }}"
                                                data-nama="{{ $lembaga->nama }}"
                                                data-is-active="{{ $lembaga->is_active ? '1' : '0' }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $lembaga->uuid }}" class="hidden expandable-content">
                                    <td colspan="3" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    {{-- Kolom Kiri: Admin Lembaga --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Data Admin Lembaga</h4>
                                                        <div class="space-y-3">
                                                            @if($lembaga->admin_nama)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Nama Admin</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $lembaga->admin_nama }}</p>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            @if($lembaga->admin_telepon)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Telepon Admin</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $lembaga->admin_telepon }}</p>
                                                                </div>
                                                            </div>
                                                            @endif

                                                            @if($lembaga->admin_email)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Email Admin</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $lembaga->admin_email }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom Kanan: Lokasi & Status --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Lokasi & Status</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Alamat</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $lembaga->alamat_lengkap }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Kode Lembaga</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $lembaga->kode_lembaga }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Status</p>
                                                                    @if($lembaga->is_active)
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            Aktif
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                            Nonaktif
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tombol Detail di Expandable Content --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end">
                                                    <a href="{{ route('lembaga.show', $lembaga->uuid) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Lihat Detail Lengkap
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($lembagas as $lembaga)
                        <div class="expandable-card">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $lembaga->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $lembaga->nama }}</h3>
                                            <div class="flex items-center mt-1">
                                                @if($lembaga->is_active)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $lembaga->kode_lembaga }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $lembaga->uuid }}"
                                            data-nama="{{ $lembaga->nama }}"
                                            data-is-active="{{ $lembaga->is_active ? '1' : '0' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                            </svg>
                                        </button>
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile Expandable Content --}}
                            <div id="detail-mobile-{{ $lembaga->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">
                                        {{-- Admin Lembaga --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Data Admin Lembaga</h4>
                                            <div class="space-y-2">
                                                @if($lembaga->admin_nama)
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $lembaga->admin_nama }}</span>
                                                </div>
                                                @endif

                                                @if($lembaga->admin_telepon)
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $lembaga->admin_telepon }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Lokasi --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Lokasi</h4>
                                            <div class="flex items-start text-sm">
                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span class="text-gray-900">{{ $lembaga->alamat }}, {{ $lembaga->kota_nama }}</span>
                                            </div>
                                        </div>

                                        {{-- Tombol Detail --}}
                                        <div class="pt-3 border-t border-gray-200">
                                            <a href="{{ route('lembaga.show', $lembaga->uuid) }}"
                                                class="inline-flex items-center justify-center w-full px-3 py-2 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                Lihat Detail Lengkap
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($lembagas->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $lembagas->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    @if(request('q') || request('provinsi_kode') || request('status'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada lembaga yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ route('lembaga.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Lembaga</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan data lembaga untuk mengelola informasi lembaga.</p>
                        <a href="{{ route('lembaga.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Lembaga
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Dropdown Container --}}
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#" id="dropdown-show-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                </a>
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>

                {{-- Toggle Status Button --}}
                <button type="button" id="dropdown-toggle-status-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm transition-colors">
                    <svg id="dropdown-toggle-status-icon-nonaktif" class="w-4 h-4 mr-3 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    <svg id="dropdown-toggle-status-icon-aktif" class="w-4 h-4 mr-3 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="dropdown-toggle-status-label">Nonaktifkan</span>
                </button>

                <div class="border-t border-gray-100 my-1"></div>

                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Toggle Status Modal --}}
    <div id="toggle-status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <div id="toggle-status-icon-wrapper-nonaktif" class="hidden">
                    <svg class="h-8 w-8 sm:h-10 sm:w-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div id="toggle-status-icon-wrapper-aktif" class="hidden">
                    <svg class="h-8 w-8 sm:h-10 sm:w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 id="toggle-status-modal-title" class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">
                Nonaktifkan Lembaga
            </h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin
                <span id="toggle-status-action-label" class="font-semibold text-gray-700">menonaktifkan</span>
                lembaga "<span id="modal-toggle-lembaga-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p id="toggle-status-modal-desc" class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">
                Lembaga yang dinonaktifkan tidak akan bisa diakses.
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-toggle-status-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-toggle-status-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Lembaga</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus lembaga
                "<span id="modal-lembaga-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentDropdownData = null;
        let currentLembagaIsActive = null;

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const showLink = document.getElementById('dropdown-show-link');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const toggleStatusBtn = document.getElementById('dropdown-toggle-status-btn');
            const toggleStatusLabel = document.getElementById('dropdown-toggle-status-label');
            const toggleStatusIconNonaktif = document.getElementById('dropdown-toggle-status-icon-nonaktif');
            const toggleStatusIconAktif = document.getElementById('dropdown-toggle-status-icon-aktif');

            // ── Desktop Expandable Rows ───────────────────────────────────
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a') || e.target.closest('.dropdown-toggle')) return;
                    const targetRow = document.getElementById(this.getAttribute('data-target'));
                    const icon = this.querySelector('.expand-icon');
                    targetRow.classList.toggle('hidden');
                    icon.classList.toggle('rotate-90');
                });
            });

            // ── Mobile Expandable Cards ───────────────────────────────────
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a') || e.target.closest('.dropdown-toggle')) return;
                    const targetContent = document.getElementById(this.getAttribute('data-target'));
                    const icon = this.querySelector('.expand-icon-mobile');
                    targetContent.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                });
            });

            // ── Dropdown ──────────────────────────────────────────────────
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');
                if (toggle) {
                    e.stopPropagation();
                    const dropdownUuid = toggle.getAttribute('data-uuid');
                    const lembagaName = toggle.getAttribute('data-nama');
                    const isActive = toggle.getAttribute('data-is-active') === '1';

                    if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);
                    const rect = toggle.getBoundingClientRect();
                    const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
                    const dropdownHeight = 160;

                    let top = rect.bottom + 4;
                    let left = rect.left;

                    if (left + dropdownWidth > window.innerWidth) left = window.innerWidth - dropdownWidth - 10;
                    if (top + dropdownHeight > window.innerHeight) top = rect.top - dropdownHeight - 4;

                    dropdownContainer.style.top = top + 'px';
                    dropdownContainer.style.left = left + 'px';
                    showLink.href = `/lembaga/${dropdownUuid}`;
                    editLink.href = `/lembaga/${dropdownUuid}/edit`;
                    currentDropdownData = { uuid: dropdownUuid, name: lembagaName };
                    currentLembagaIsActive = isActive;

                    // Update toggle status button appearance
                    if (isActive) {
                        toggleStatusLabel.textContent = 'Nonaktifkan';
                        toggleStatusBtn.className = 'flex items-center w-full px-3 sm:px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 transition-colors';
                        toggleStatusIconNonaktif.classList.remove('hidden');
                        toggleStatusIconAktif.classList.add('hidden');
                    } else {
                        toggleStatusLabel.textContent = 'Aktifkan';
                        toggleStatusBtn.className = 'flex items-center w-full px-3 sm:px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition-colors';
                        toggleStatusIconNonaktif.classList.add('hidden');
                        toggleStatusIconAktif.classList.remove('hidden');
                    }

                    dropdownContainer.classList.remove('hidden');
                } else {
                    if (!dropdownContainer.contains(e.target)) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                    }
                }
            });

            // ── Toggle Status ─────────────────────────────────────────────
            toggleStatusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!currentDropdownData) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                const isActive = currentLembagaIsActive;
                const modal = document.getElementById('toggle-status-modal');
                const modalTitle = document.getElementById('toggle-status-modal-title');
                const actionLabel = document.getElementById('toggle-status-action-label');
                const modalDesc = document.getElementById('toggle-status-modal-desc');
                const confirmBtn = document.getElementById('confirm-toggle-status-btn');
                const iconWrapperNonaktif = document.getElementById('toggle-status-icon-wrapper-nonaktif');
                const iconWrapperAktif = document.getElementById('toggle-status-icon-wrapper-aktif');

                document.getElementById('modal-toggle-lembaga-name').textContent = currentDropdownData.name;

                if (isActive) {
                    modalTitle.textContent = 'Nonaktifkan Lembaga';
                    actionLabel.textContent = 'menonaktifkan';
                    modalDesc.textContent = 'Lembaga yang dinonaktifkan tidak akan bisa diakses oleh pengguna.';
                    confirmBtn.className = 'w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors';
                    iconWrapperNonaktif.classList.remove('hidden');
                    iconWrapperAktif.classList.add('hidden');
                } else {
                    modalTitle.textContent = 'Aktifkan Lembaga';
                    actionLabel.textContent = 'mengaktifkan';
                    modalDesc.textContent = 'Lembaga yang diaktifkan kembali akan bisa diakses oleh pengguna.';
                    confirmBtn.className = 'w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors';
                    iconWrapperNonaktif.classList.add('hidden');
                    iconWrapperAktif.classList.remove('hidden');
                }

                modal.classList.remove('hidden');
            });

            document.getElementById('confirm-toggle-status-btn').addEventListener('click', function() {
                if (!currentDropdownData) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/lembaga/${currentDropdownData.uuid}/toggle-status`;
                const csrf = document.createElement('input');
                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input');
                method.type = 'hidden'; method.name = '_method'; method.value = 'PATCH';
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            });

            document.getElementById('cancel-toggle-status-btn').addEventListener('click', function() {
                document.getElementById('toggle-status-modal').classList.add('hidden');
            });

            document.getElementById('toggle-status-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // ── Delete ────────────────────────────────────────────────────
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!currentDropdownData) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
                document.getElementById('modal-lembaga-name').textContent = currentDropdownData.name;
                document.getElementById('delete-modal').classList.remove('hidden');
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                if (!currentDropdownData) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/lembaga/${currentDropdownData.uuid}`;
                const csrf = document.createElement('input');
                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                const method = document.createElement('input');
                method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            });

            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            window.addEventListener('scroll', function() {
                dropdownContainer.classList.add('hidden');
            }, true);

            window.addEventListener('resize', function() {
                dropdownContainer.classList.add('hidden');
            });
        });

        // ── Toggle Search ─────────────────────────────────────────────────
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

        // ── Toggle Filter Panel ───────────────────────────────────────────
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // ── ESC menutup search form & modal ──────────────────────────────
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
                document.getElementById('delete-modal').classList.add('hidden');
                document.getElementById('toggle-status-modal').classList.add('hidden');
            }
        });
    </script>
@endpush