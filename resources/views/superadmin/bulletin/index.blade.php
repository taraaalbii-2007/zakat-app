@extends('layouts.app')

@section('title', 'Kelola Bulletin')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

           <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Kelola Bulletin</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi bulletin</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'status', 'kategori', 'sumber']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>

                        <!-- Tombol Tambah - DIPERBAIKI -->
                        <a href="{{ route('superadmin.bulletin.create') }}"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Bulletin
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pending Alert - DIPERBAIKI -->
            @if($pendingCount > 0)
                <div class="mx-5 mt-4 flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm text-amber-800 flex-1">
                        Ada <strong>{{ $pendingCount }} bulletin</strong> dari lembaga yang menunggu persetujuan Anda.
                    </p>
                    <a href="{{ route('superadmin.bulletin.index', ['status' => 'pending']) }}"
                       class="flex-shrink-0 inline-flex items-center px-3 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-800 text-xs font-medium rounded-lg transition-all">
                        Lihat
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif

            <!-- Statistik Bar - DIPERBAIKI -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $bulletins->total() }}</span>
                        <span class="text-sm text-gray-500">Bulletin</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'status', 'kategori', 'sumber']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.bulletin.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Bulletin</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari judul, konten, lokasi..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>

                            <!-- Kategori Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Kategori</label>
                                <select name="kategori"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sumber Filter -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Sumber</label>
                                <select name="sumber"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Sumber</option>
                                    <option value="superadmin" {{ request('sumber') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                    <option value="lembaga" {{ request('sumber') == 'lembaga' ? 'selected' : '' }}>Lembaga</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'kategori', 'sumber']))
                            <a href="{{ route('superadmin.bulletin.index') }}"
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
            @if(request()->hasAny(['q', 'status', 'kategori', 'sumber']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
                        @php $statusLabels = ['pending' => 'Pending', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'draft' => 'Draft']; @endphp
                        
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
                                Status: {{ $statusLabels[request('status')] ?? request('status') }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        
                        @if(request('kategori'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Kategori: {{ $kategoriList->firstWhere('id', request('kategori'))?->nama_kategori ?? request('kategori') }}
                                <button onclick="removeFilter('kategori')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        
                        @if(request('sumber'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Sumber: {{ request('sumber') === 'lembaga' ? 'Dari Lembaga' : request('sumber') }}
                                <button onclick="removeFilter('sumber')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Tabel -->
            @if($bulletins->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">BULLETIN</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SUMBER</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($bulletins as $bulletin)
                                @php
                                    $statusCfg = [
                                        'draft'    => ['bg-gray-100 text-gray-700', 'Draft'],
                                        'pending'  => ['bg-amber-50 text-amber-700 border border-amber-200', 'Pending'],
                                        'approved' => ['bg-green-50 text-green-700 border border-green-200', 'Disetujui'],
                                        'rejected' => ['bg-red-50 text-red-700 border border-red-200', 'Ditolak'],
                                    ];
                                    [$statusCls, $statusLbl] = $statusCfg[$bulletin->status] ?? ['bg-gray-100 text-gray-700', $bulletin->status];
                                @endphp

                                <!-- Baris Utama -->
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300 cursor-pointer expandable-row {{ $bulletin->isPending() ? 'bg-amber-50/30' : '' }}"
                                    data-target="detail-{{ $bulletin->uuid }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block" 
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                                @if($bulletin->thumbnail)
                                                    <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                    {{ Str::limit($bulletin->judul, 50) }}
                                                </span>
                                                <div class="text-xs text-gray-400 mt-0.5">Klik untuk detail</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($bulletin->lembaga)
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ Str::limit($bulletin->lembaga->nama, 20) }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                                Superadmin
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusCls }}">
                                            {{ $statusLbl }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800">{{ $bulletin->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $bulletin->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Ikon Lihat dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('superadmin.bulletin.show', $bulletin->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Lihat Detail
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>

                                            <!-- Ikon Setujui (hanya untuk pending) -->
                                            @if($bulletin->isPending())
                                                <div class="relative group/tooltip">
                                                    <form action="{{ route('superadmin.bulletin.approve', $bulletin->uuid) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            onclick="return confirm('Setujui bulletin ini?')"
                                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Setujui
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Ikon Edit (hanya untuk superadmin) -->
                                            @if(is_null($bulletin->lembaga_id))
                                                <div class="relative group/tooltip">
                                                    <a href="{{ route('superadmin.bulletin.edit', $bulletin->uuid) }}"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Edit
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Ikon Hapus dengan Tooltip -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $bulletin->uuid }}" data-judul="{{ addslashes($bulletin->judul) }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Hapus
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Baris Expandable Desktop -->
                                <tr id="detail-{{ $bulletin->uuid }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="5" class="px-6 py-4 bg-gray-50/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Kolom 1: Informasi Dasar -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Author</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $bulletin->author->username ?? 'Admin' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-xs text-gray-500">Kategori</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $bulletin->kategoriBulletin->nama_kategori ?? '-' }}</span>
                                                    </div>
                                                    @if($bulletin->lokasi)
                                                        <div class="flex justify-between">
                                                            <span class="text-xs text-gray-500">Lokasi</span>
                                                            <span class="text-xs font-medium text-gray-700">{{ $bulletin->lokasi }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Kolom 2: Ringkasan Konten -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ringkasan Konten</h4>
                                                @if($bulletin->konten)
                                                    <p class="text-xs text-gray-600">{{ Str::limit(strip_tags($bulletin->konten), 150) }}</p>
                                                @else
                                                    <p class="text-xs text-gray-400 italic">Tidak ada konten</p>
                                                @endif
                                            </div>

                                            <!-- Kolom 3: Metadata & Catatan -->
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Riwayat</h4>
                                                <div class="space-y-1.5 text-xs">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-500">Dibuat</span>
                                                        <span class="font-medium text-gray-700">{{ $bulletin->created_at->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    @if($bulletin->updated_at != $bulletin->created_at)
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Diperbarui</span>
                                                            <span class="font-medium text-gray-700">{{ $bulletin->updated_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif
                                                    @if($bulletin->approved_at)
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-500">Disetujui</span>
                                                            <span class="font-medium text-green-700">{{ $bulletin->approved_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($bulletin->catatan_penolakan && $bulletin->status === 'rejected')
                                                    <div class="mt-3 p-2 bg-red-50 rounded-lg border border-red-100">
                                                        <p class="text-xs font-medium text-red-700 mb-0.5">Catatan Penolakan</p>
                                                        <p class="text-xs text-red-600">{{ $bulletin->catatan_penolakan }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach($bulletins as $bulletin)
                        @php
                            $statusCfg = [
                                'draft'    => ['bg-gray-100 text-gray-700', 'Draft'],
                                'pending'  => ['bg-amber-50 text-amber-700 border border-amber-200', 'Pending'],
                                'approved' => ['bg-green-50 text-green-700 border border-green-200', 'Disetujui'],
                                'rejected' => ['bg-red-50 text-red-700 border border-red-200', 'Ditolak'],
                            ];
                            [$statusCls, $statusLbl] = $statusCfg[$bulletin->status] ?? ['bg-gray-100 text-gray-700', $bulletin->status];
                        @endphp
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 {{ $bulletin->isPending() ? 'bg-amber-50/30' : '' }}">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $bulletin->uuid }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile" 
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Bulletin</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 break-words">
                                            {{ Str::limit($bulletin->judul, 60) }}
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusCls }}">
                                                {{ $statusLbl }}
                                            </span>
                                            @if($bulletin->lembaga)
                                                <span class="text-xs text-blue-600 font-medium">{{ Str::limit($bulletin->lembaga->nama, 20) }}</span>
                                            @else
                                                <span class="text-xs text-purple-600 font-medium">Superadmin</span>
                                            @endif
                                            <span class="text-xs text-gray-400">{{ $bulletin->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Lihat Detail -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('superadmin.bulletin.show', $bulletin->uuid) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Lihat
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>

                                        <!-- Setujui (pending only) -->
                                        @if($bulletin->isPending())
                                            <div class="relative group/tooltip">
                                                <form action="{{ route('superadmin.bulletin.approve', $bulletin->uuid) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return confirm('Setujui bulletin ini?')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Setujui
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Edit (superadmin only) -->
                                        @if(is_null($bulletin->lembaga_id))
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('superadmin.bulletin.edit', $bulletin->uuid) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Edit
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Hapus -->
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $bulletin->uuid }}" data-judul="{{ addslashes($bulletin->judul) }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                Hapus
                                                <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $bulletin->uuid }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Informasi Dasar</h4>
                                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Author</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $bulletin->author->username ?? 'Admin' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-xs text-gray-500">Kategori</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $bulletin->kategoriBulletin->nama_kategori ?? '-' }}</span>
                                            </div>
                                            @if($bulletin->lokasi)
                                                <div class="flex justify-between">
                                                    <span class="text-xs text-gray-500">Lokasi</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ $bulletin->lokasi }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($bulletin->konten)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ringkasan</h4>
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <p class="text-xs text-gray-600">{{ Str::limit(strip_tags($bulletin->konten), 100) }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    @if($bulletin->catatan_penolakan && $bulletin->status === 'rejected')
                                        <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                                            <p class="text-xs font-medium text-red-700 mb-1">Catatan Penolakan</p>
                                            <p class="text-xs text-red-600">{{ $bulletin->catatan_penolakan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($bulletins->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $bulletins->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="relative inline-block">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                    </div>

                    @if(request('q') || request('status') || request('kategori') || request('sumber'))
                        <p class="text-sm text-gray-500 mb-2">
                            @if(request('q'))
                                Tidak ada bulletin yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada data yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <button onclick="resetAllFilters()"
                            class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset filter
                        </button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada bulletin</p>
                        <a href="{{ route('superadmin.bulletin.create') }}"
                            class="inline-flex items-center gap-1 text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat bulletin sekarang
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fade-in">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300 animate-scale-in">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Bulletin</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus bulletin "<span id="modal-judul" class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete-btn"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
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

        const filterButton = document.getElementById('filterButton');
        const filterPanel = document.getElementById('filterPanel');
        const closeFilterPanelBtn = document.getElementById('closeFilterPanelBtn');

        if (filterButton && filterPanel) {
            filterButton.addEventListener('click', function() {
                filterPanel.classList.toggle('hidden');
            });
        }

        if (closeFilterPanelBtn && filterPanel) {
            closeFilterPanelBtn.addEventListener('click', function() {
                filterPanel.classList.add('hidden');
            });
        }

        // Desktop Expandable row
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn') || e.target.closest('a') || e.target.closest('button[type="submit"]')) return;
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
                if (e.target.closest('.delete-btn') || e.target.closest('a') || e.target.closest('button[type="submit"]')) return;
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                }
            });
        });

        // Delete button handler
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const uuid = this.getAttribute('data-uuid');
                const judul = this.getAttribute('data-judul');
                document.getElementById('modal-judul').textContent = judul;
                deleteForm.action = `/bulletin/${uuid}`;
                deleteModal.classList.remove('hidden');
            });
        });

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });
        }

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

    function resetAllFilters() {
        const url = new URL(window.location.href);
        url.searchParams.delete('q');
        url.searchParams.delete('status');
        url.searchParams.delete('kategori');
        url.searchParams.delete('sumber');
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }
</script>
@endpush