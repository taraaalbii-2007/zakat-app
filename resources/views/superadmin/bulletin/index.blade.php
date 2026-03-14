@extends('layouts.app')

@section('title', 'Kelola Bulletin')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- Pending Alert --}}
        @if($pendingCount > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-center gap-3 animate-slide-up">
                <svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-sm text-yellow-800 flex-1">
                    Ada <strong>{{ $pendingCount }} bulletin</strong> dari lembaga yang menunggu persetujuan Anda.
                </p>
                <a href="{{ route('superadmin.bulletin.index', ['status' => 'pending']) }}"
                   class="flex-shrink-0 text-xs font-medium text-yellow-700 hover:text-yellow-900 underline">
                    Lihat Sekarang →
                </a>
            </div>
        @endif

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Kelola Bulletin</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $bulletins->total() }} Bulletin</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('superadmin.bulletin.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Buat Bulletin
                            </span>
                        </a>
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Filter
                            </span>
                        </button>
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Cari
                                </span>
                            </button>
                            <form method="GET" action="{{ route('superadmin.bulletin.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('kategori'))
                                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                                @endif
                                @if(request('sumber'))
                                    <input type="hidden" name="sumber" value="{{ request('sumber') }}">
                                @endif
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari judul, konten, lokasi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel" class="{{ (request('kategori')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('superadmin.bulletin.index') }}" id="filter-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request('sumber'))
                        <input type="hidden" name="sumber" value="{{ request('sumber') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori" id="filter-kategori"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriList as $kat)
                                    <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(request('kategori'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('superadmin.bulletin.index', array_filter(['q' => request('q'), 'status' => request('status'), 'sumber' => request('sumber')])) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            @if($bulletins->count() > 0)

                {{-- Badge Filter Aktif --}}
                @if(request('q') || request('kategori') || request('status') || request('sumber'))
                    <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                        <div class="flex items-center flex-wrap gap-2">
                            <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                            @if(request('q'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Pencarian: "{{ request('q') }}"
                                    <button type="button" onclick="removeFilter('q')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if(request('status'))
                                @php
                                    $statusLabels = ['pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'draft' => 'Draft'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Status: {{ $statusLabels[request('status')] ?? request('status') }}
                                    <button type="button" onclick="removeFilter('status')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if(request('kategori'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Kategori: {{ $kategoriList->firstWhere('id', request('kategori'))?->nama_kategori ?? request('kategori') }}
                                    <button type="button" onclick="removeFilter('kategori')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                            @if(request('sumber'))
                                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Sumber: {{ request('sumber') === 'lembaga' ? 'Dari Lembaga' : request('sumber') }}
                                    <button type="button" onclick="removeFilter('sumber')" class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Desktop View --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bulletin
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sumber
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bulletins as $bulletin)
                                @php
                                    $statusCfg = [
                                        'draft'    => ['bg-gray-100 text-gray-700',    'Draft'],
                                        'pending'  => ['bg-yellow-100 text-yellow-700','Pending'],
                                        'approved' => ['bg-green-100 text-green-700',  'Disetujui'],
                                        'rejected' => ['bg-red-100 text-red-700',      'Ditolak'],
                                    ];
                                    [$statusCls, $statusLbl] = $statusCfg[$bulletin->status] ?? ['bg-gray-100 text-gray-700', $bulletin->status];
                                @endphp

                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row {{ $bulletin->isPending() ? 'bg-yellow-50/30' : '' }}"
                                    data-target="detail-{{ $bulletin->uuid }}">
                                    <td class="px-4 py-4">
                                        <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-md bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                                @if($bulletin->thumbnail)
                                                    <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900 line-clamp-1">
                                                    {{ $bulletin->judul }}
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($bulletin->lembaga)
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                {{ Str::limit($bulletin->lembaga->nama, 20) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-50 text-purple-700">
                                                Superadmin
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusCls }}">
                                            {{ $statusLbl }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $bulletin->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $bulletin->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                data-uuid="{{ $bulletin->uuid }}"
                                                data-judul="{{ addslashes($bulletin->judul) }}"
                                                data-status="{{ $bulletin->status }}"
                                                data-lembaga="{{ $bulletin->lembaga_id ? '1' : '0' }}"
                                                data-pending="{{ $bulletin->isPending() ? '1' : '0' }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Expandable Detail Row --}}
                                <tr id="detail-{{ $bulletin->uuid }}" class="hidden expandable-content">
                                    <td colspan="6" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                    {{-- Kolom 1: Informasi Dasar --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Dasar</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Author</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $bulletin->author->username ?? 'Admin' }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Sumber</p>
                                                                    @if($bulletin->lembaga)
                                                                        <p class="text-sm font-medium text-blue-700">{{ $bulletin->lembaga->nama }}</p>
                                                                    @else
                                                                        <p class="text-sm font-medium text-purple-700">Superadmin</p>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Kategori</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $bulletin->kategoriBulletin->nama_kategori ?? '-' }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 mt-0.5 mr-2 flex-shrink-0 {{ $bulletin->isPending() ? 'text-yellow-500' : ($bulletin->status === 'approved' ? 'text-green-500' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Status</p>
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusCls }}">
                                                                        {{ $statusLbl }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 2: Konten Ringkas --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Ringkasan Konten</h4>
                                                        <div class="space-y-3">
                                                            @if($bulletin->thumbnail)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Thumbnail</p>
                                                                        <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="thumbnail"
                                                                            class="mt-1 w-24 h-16 object-cover rounded-md border border-gray-200">
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($bulletin->konten)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Konten</p>
                                                                        <p class="text-sm text-gray-600 mt-0.5">{{ Str::limit(strip_tags($bulletin->konten), 120) }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($bulletin->lokasi)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Lokasi</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $bulletin->lokasi }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Kolom 3: Metadata --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Metadata</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Dibuat</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $bulletin->created_at->format('d F Y') }}</p>
                                                                    <p class="text-xs text-gray-400">{{ $bulletin->created_at->format('H:i') }}</p>
                                                                </div>
                                                            </div>

                                                            @if($bulletin->updated_at != $bulletin->created_at)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Diperbarui</p>
                                                                        <p class="text-sm font-medium text-gray-900">{{ $bulletin->updated_at->format('d F Y') }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($bulletin->approved_at)
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Disetujui</p>
                                                                        <p class="text-sm font-medium text-green-700">{{ $bulletin->approved_at->format('d F Y') }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($bulletin->catatan_penolakan && $bulletin->status === 'rejected')
                                                                <div class="flex items-start">
                                                                    <svg class="w-4 h-4 text-red-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                    </svg>
                                                                    <div>
                                                                        <p class="text-xs text-gray-500">Catatan Penolakan</p>
                                                                        <p class="text-sm text-red-600">{{ $bulletin->catatan_penolakan }}</p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Tombol Aksi di Expandable Content --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                                    <div></div>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('superadmin.bulletin.show', $bulletin->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                            Lihat Detail
                                                        </a>

                                                        @if($bulletin->isPending())
                                                            <form action="{{ route('superadmin.bulletin.approve', $bulletin->uuid) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    onclick="return confirm('Setujui bulletin ini?')"
                                                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                    Setujui
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if(is_null($bulletin->lembaga_id))
                                                            <a href="{{ route('superadmin.bulletin.edit', $bulletin->uuid) }}"
                                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                </svg>
                                                                Edit
                                                            </a>
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

                {{-- Mobile View --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach($bulletins as $bulletin)
                        @php
                            $statusCfg = ['draft'=>'bg-gray-100 text-gray-700','pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700'];
                            $statusLabels = ['draft'=>'Draft','pending'=>'Pending','approved'=>'Disetujui','rejected'=>'Ditolak'];
                        @endphp
                        <div class="expandable-card">
                            <div class="p-4 {{ $bulletin->isPending() ? 'bg-yellow-50/50' : 'hover:bg-gray-50' }} transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $bulletin->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0 gap-2">
                                        <div class="w-9 h-9 rounded-md bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                            @if($bulletin->thumbnail)
                                                <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $bulletin->judul }}
                                            </h3>
                                            <div class="flex items-center flex-wrap gap-1.5 mt-1">
                                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $statusCfg[$bulletin->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                    {{ $statusLabels[$bulletin->status] ?? $bulletin->status }}
                                                </span>
                                                @if($bulletin->lembaga)
                                                    <span class="text-[10px] text-blue-600 font-medium">{{ Str::limit($bulletin->lembaga->nama, 18) }}</span>
                                                @else
                                                    <span class="text-[10px] text-purple-600 font-medium">Superadmin</span>
                                                @endif
                                                <span class="text-[10px] text-gray-400">{{ $bulletin->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $bulletin->uuid }}"
                                            data-judul="{{ addslashes($bulletin->judul) }}"
                                            data-status="{{ $bulletin->status }}"
                                            data-lembaga="{{ $bulletin->lembaga_id ? '1' : '0' }}"
                                            data-pending="{{ $bulletin->isPending() ? '1' : '0' }}">
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
                            <div id="detail-mobile-{{ $bulletin->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">

                                        {{-- Author & Sumber --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Informasi</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm gap-2">
                                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $bulletin->author->username ?? 'Admin' }}</span>
                                                </div>
                                                @if($bulletin->kategori)
                                                    <div class="flex items-center text-sm gap-2">
                                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                        </svg>
                                                        <span class="text-gray-900">{{ $bulletin->kategori->nama_kategori }}</span>
                                                    </div>
                                                @endif
                                                @if($bulletin->lokasi)
                                                    <div class="flex items-center text-sm gap-2">
                                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span class="text-gray-900">{{ $bulletin->lokasi }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Konten ringkas --}}
                                        @if($bulletin->konten)
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Ringkasan</h4>
                                                <p class="text-sm text-gray-600">{{ Str::limit(strip_tags($bulletin->konten), 100) }}</p>
                                            </div>
                                        @endif

                                        {{-- Catatan penolakan --}}
                                        @if($bulletin->catatan_penolakan && $bulletin->status === 'rejected')
                                            <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                                                <p class="text-xs font-medium text-red-700 mb-1">Catatan Penolakan</p>
                                                <p class="text-sm text-red-600">{{ $bulletin->catatan_penolakan }}</p>
                                            </div>
                                        @endif

                                        {{-- Tombol Aksi Mobile --}}
                                        <div class="pt-3 border-t border-gray-200">
                                            <div class="flex gap-2">
                                                <a href="{{ route('superadmin.bulletin.show', $bulletin->uuid) }}"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Lihat
                                                </a>

                                                @if($bulletin->isPending())
                                                    <form action="{{ route('superadmin.bulletin.approve', $bulletin->uuid) }}" method="POST" class="flex-1">
                                                        @csrf
                                                        <button type="submit"
                                                            onclick="return confirm('Setujui bulletin ini?')"
                                                            class="w-full inline-flex items-center justify-center px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                            Setujui
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(is_null($bulletin->lembaga_id))
                                                    <a href="{{ route('superadmin.bulletin.edit', $bulletin->uuid) }}"
                                                        class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        Edit
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($bulletins->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $bulletins->links() }}
                    </div>
                @endif

            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    @if(request('q') || request('status') || request('kategori') || request('sumber'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Bulletin Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if(request('q'))
                                Tidak ada bulletin yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada data yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('superadmin.bulletin.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Bulletin</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai buat bulletin baru untuk dipublikasikan.</p>
                        <a href="{{ route('superadmin.bulletin.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Bulletin
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
                <a href="#" id="dropdown-view-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                </a>
                <div id="dropdown-approve-wrapper">
                    <form id="dropdown-approve-form" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            onclick="return confirm('Setujui bulletin ini?')"
                            class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors text-left">
                            <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui
                        </button>
                    </form>
                </div>
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Bulletin</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus bulletin
                "<span id="modal-judul" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition-colors">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentDropdownData = null;

        document.addEventListener('DOMContentLoaded', function () {
            const dropdownContainer = document.getElementById('dropdown-container');
            const viewLink          = document.getElementById('dropdown-view-link');
            const approveWrapper    = document.getElementById('dropdown-approve-wrapper');
            const approveForm       = document.getElementById('dropdown-approve-form');
            const editLink          = document.getElementById('dropdown-edit-link');
            const deleteBtn         = document.getElementById('dropdown-delete-btn');
            const deleteForm        = document.getElementById('delete-form');
            const tableContainer    = document.getElementById('table-container');
            const filterKategori    = document.getElementById('filter-kategori');
            const filterForm        = document.getElementById('filter-form');

            // Filter kategori auto-submit
            if (filterKategori) {
                filterKategori.addEventListener('change', function () {
                    filterForm.submit();
                });
            }

            // Desktop Expandable Rows
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('.dropdown-toggle') || e.target.closest('button[type="submit"]')) return;

                    const targetId  = this.getAttribute('data-target');
                    const targetRow = document.getElementById(targetId);
                    const icon      = this.querySelector('.expand-icon');

                    if (targetRow.classList.contains('hidden')) {
                        targetRow.classList.remove('hidden');
                        icon.classList.add('rotate-90');
                    } else {
                        targetRow.classList.add('hidden');
                        icon.classList.remove('rotate-90');
                    }
                });
            });

            // Mobile Expandable Cards
            document.querySelectorAll('.expandable-row-mobile').forEach(row => {
                row.addEventListener('click', function (e) {
                    if (e.target.closest('a') || e.target.closest('.dropdown-toggle') || e.target.closest('button[type="submit"]')) return;

                    const targetId      = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon          = this.querySelector('.expand-icon-mobile');

                    if (targetContent.classList.contains('hidden')) {
                        targetContent.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        targetContent.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                });
            });

            // Dropdown logic
            document.addEventListener('click', function (e) {
                const toggle = e.target.closest('.dropdown-toggle');
                if (toggle) {
                    e.stopPropagation();

                    const uuid      = toggle.getAttribute('data-uuid');
                    const judul     = toggle.getAttribute('data-judul');
                    const isPending = toggle.getAttribute('data-pending') === '1';
                    const hasLembaga= toggle.getAttribute('data-lembaga') === '1';

                    if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    dropdownContainer.setAttribute('data-current-uuid', uuid);

                    const rect          = toggle.getBoundingClientRect();
                    const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
                    const dropdownHeight= isPending ? 148 : (!hasLembaga ? 132 : 100);

                    let top  = rect.bottom;
                    let left = rect.left;

                    if (left + dropdownWidth > window.innerWidth) left = window.innerWidth - dropdownWidth - 10;
                    if (top + dropdownHeight > window.innerHeight) top = rect.top - dropdownHeight;

                    dropdownContainer.style.top  = top + 'px';
                    dropdownContainer.style.left = left + 'px';

                    // Update link & form targets
                    viewLink.href         = `/bulletin/${uuid}`;
                    approveForm.action    = `/bulletin/${uuid}/approve`;
                    editLink.href         = `/bulletin/${uuid}/edit`;

                    // Show/hide approve
                    approveWrapper.style.display = isPending  ? '' : 'none';
                    // Show/hide edit (hanya untuk superadmin punya)
                    editLink.style.display       = !hasLembaga ? '' : 'none';

                    currentDropdownData = { uuid, judul };
                    dropdownContainer.classList.remove('hidden');

                } else {
                    if (!dropdownContainer.contains(e.target)) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                    }
                }
            });

            // Delete btn
            deleteBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!currentDropdownData?.uuid) return;

                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                document.getElementById('modal-judul').textContent = currentDropdownData.judul;
                deleteForm.action = `/bulletin/${currentDropdownData.uuid}`;
                document.getElementById('delete-modal').classList.remove('hidden');
            });

            document.getElementById('cancel-delete-btn').addEventListener('click', function () {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('delete-modal').addEventListener('click', function (e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // Dismiss dropdown on scroll / resize
            window.addEventListener('scroll', function () {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            }, true);

            if (tableContainer) {
                tableContainer.addEventListener('scroll', function () {
                    if (!dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                    }
                }, true);
            }

            window.addEventListener('resize', function () {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });
        });

        function toggleSearch() {
            const searchButton    = document.getElementById('search-button');
            const searchForm      = document.getElementById('search-form');
            const searchInput     = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');

            if (searchForm.classList.contains('hidden')) {
                searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput.focus(), 50);
            } else {
                const hasQuery = '{{ request('q') }}' !== '';
                if (!hasQuery) searchInput.value = '';
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endpush