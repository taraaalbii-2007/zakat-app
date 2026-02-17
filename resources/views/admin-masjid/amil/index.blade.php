@extends('layouts.app')

@section('title', 'Kelola Data Amil')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Amil</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $amils->total() }} Amil</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('amil.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Tambah
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
                            style="{{ request('search') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('search') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span id="search-button-text"
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Cari
                                </span>
                            </button>
                            <form method="GET" action="{{ route('amil.index') }}" id="search-form"
                                class="{{ request('search') ? '' : 'hidden' }}">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('masjid_id'))
                                    <input type="hidden" name="masjid_id" value="{{ request('masjid_id') }}">
                                @endif
                                @if(request('jenis_kelamin'))
                                    <input type="hidden" name="jenis_kelamin" value="{{ request('jenis_kelamin') }}">
                                @endif
                                @if(request('sort_by'))
                                    <input type="hidden" name="sort_by" value="{{ request('sort_by') }}">
                                @endif
                                @if(request('sort_order'))
                                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                @endif
                                <div class="flex items-center">
                                    <div class="relative flex-1">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="search" name="search" value="{{ request('search') }}" id="search-input"
                                            placeholder="Cari amil..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel" class="{{ (request('status') || request('masjid_id') || request('jenis_kelamin') || request('sort_by')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('amil.index') }}" id="filter-form">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="filter-status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            </select>
                        </div>
                        
                        @if(auth()->user()->role === 'superadmin')
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Masjid</label>
                            <select name="masjid_id" id="filter-masjid"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Masjid</option>
                                @foreach($masjids as $masjid)
                                    <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>
                                        {{ $masjid->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="filter-jenis-kelamin"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Urut Berdasarkan</label>
                            <select name="sort_by" id="filter-sort-by"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                                <option value="nama_lengkap" {{ request('sort_by') == 'nama_lengkap' ? 'selected' : '' }}>Nama</option>
                                <option value="kode_amil" {{ request('sort_by') == 'kode_amil' ? 'selected' : '' }}>Kode</option>
                            </select>
                        </div>
                    </div>
                    @if(request('status') || request('masjid_id') || request('jenis_kelamin') || request('sort_by') || request('sort_order'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('amil.index', request('search') ? ['search' => request('search')] : []) }}"
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

            @if ($amils->count() > 0)
                {{-- Info Filter Aktif --}}
                @if(request('status') || request('masjid_id') || request('jenis_kelamin') || request('search'))
                    <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-wrap gap-2">
                                <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Pencarian: "{{ request('search') }}"
                                        <button type="button" onclick="removeFilter('search')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                                @if(request('status'))
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Status: {{ ucfirst(request('status')) }}
                                        <button type="button" onclick="removeFilter('status')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                                @if(request('masjid_id'))
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Masjid: {{ $masjids->find(request('masjid_id'))->nama ?? 'Unknown' }}
                                        <button type="button" onclick="removeFilter('masjid_id')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                                @if(request('jenis_kelamin'))
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        {{ request('jenis_kelamin') == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        <button type="button" onclick="removeFilter('jenis_kelamin')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                                @if(request('sort_by') && request('sort_by') != 'created_at')
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                        Urut: {{ ucfirst(str_replace('_', ' ', request('sort_by'))) }}
                                        <button type="button" onclick="removeFilter('sort_by')" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            ×
                                        </button>
                                    </span>
                                @endif
                            </div>
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
                                    Amil
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($amils as $amil)
                                {{-- Parent Row --}}
                                <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row" 
                                    data-target="detail-{{ $amil->uuid }}">
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
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100" 
                                                     src="{{ $amil->foto_url }}" 
                                                     alt="{{ $amil->nama_lengkap }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</div>
                                                    @if($amil->jenis_kelamin == 'L')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                                            </svg>
                                                            L
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                                            </svg>
                                                            P
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-500 mt-0.5">
                                                    {{ $amil->kode_amil }}
                                                    @if($amil->wilayah_tugas)
                                                        • {{ $amil->wilayah_tugas }}
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">Klik untuk melihat detail</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center text-sm text-gray-900">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $amil->telepon }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-500">
                                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ Str::limit($amil->email, 25) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($amil->status == 'aktif')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                                Aktif
                                            </span>
                                        @elseif($amil->status == 'cuti')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span>
                                                Cuti
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                data-uuid="{{ $amil->uuid }}"
                                                data-nama="{{ $amil->nama_lengkap }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                {{-- Expandable Content Row --}}
                                <tr id="detail-{{ $amil->uuid }}" class="hidden expandable-content">
                                    <td colspan="5" class="px-0 py-0">
                                        <div class="bg-gray-50 border-y border-gray-100">
                                            <div class="px-6 py-4">
                                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                    {{-- Kolom 1: Data Pribadi --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Data Pribadi</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Nama Lengkap</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Kode Amil</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->kode_amil }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->tempat_lahir }}, {{ $amil->tanggal_lahir->translatedFormat('d F Y') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Kolom 2: Kontak & Alamat --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Kontak & Alamat</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Telepon</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->telepon }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Email</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->email }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Alamat</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->alamat }}</p>
                                                                </div>
                                                            </div>
                                                            
                                                            @if($amil->wilayah_tugas)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Wilayah Tugas</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->wilayah_tugas }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    {{-- Kolom 3: Status & Info Tambahan --}}
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Status & Info</h4>
                                                        <div class="space-y-3">
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Status Amil</p>
                                                                    @if($amil->status == 'aktif')
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                                            Aktif
                                                                        </span>
                                                                    @elseif($amil->status == 'cuti')
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">
                                                                            Cuti
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                                            Nonaktif
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            
                                                            @if(auth()->user()->role === 'superadmin' && $amil->masjid)
                                                            <div class="flex items-start">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Masjid</p>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $amil->masjid->nama }}</p>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                                            <div class="text-xs text-gray-500 space-y-1">
                                                                <div>Bergabung: {{ $amil->created_at->format('d/m/Y') }}</div>
                                                                @if($amil->updated_at != $amil->created_at)
                                                                    <div>Diperbarui: {{ $amil->updated_at->format('d/m/Y H:i') }}</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                {{-- Tombol Aksi di Expandable Content --}}
                                                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
                                                    <div class="flex items-center text-sm text-gray-500">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        Klik tombol aksi untuk mengelola data amil
                                                    </div>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('amil.show', $amil->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                            Detail
                                                        </a>
                                                        <a href="{{ route('amil.edit', $amil->uuid) }}"
                                                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                            </svg>
                                                            Edit
                                                        </a>
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
                    @foreach ($amils as $amil)
                        <div class="expandable-card">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $amil->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0 mr-3">
                                            <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100" 
                                                 src="{{ $amil->foto_url }}" 
                                                 alt="{{ $amil->nama_lengkap }}">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $amil->nama_lengkap }}</h3>
                                                @if($amil->jenis_kelamin == 'L')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                                        L
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800 flex-shrink-0">
                                                        P
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center mt-1">
                                                @if($amil->status == 'aktif')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">
                                                        Aktif
                                                    </span>
                                                @elseif($amil->status == 'cuti')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">
                                                        Cuti
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $amil->kode_amil }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $amil->uuid }}"
                                            data-nama="{{ $amil->nama_lengkap }}">
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
                            <div id="detail-mobile-{{ $amil->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100">
                                    <div class="space-y-4">
                                        {{-- Data Pribadi --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Data Pribadi</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $amil->kode_amil }}</span>
                                                </div>
                                                
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $amil->tempat_lahir }}, {{ $amil->tanggal_lahir->translatedFormat('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Kontak --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Kontak</h4>
                                            <div class="space-y-2">
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                    </svg>
                                                    <span class="text-gray-900">{{ $amil->telepon }}</span>
                                                </div>
                                                
                                                <div class="flex items-center text-sm">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span class="text-gray-900 truncate">{{ $amil->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Alamat & Wilayah --}}
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 mb-2">Alamat & Wilayah</h4>
                                            <div class="space-y-2">
                                                <p class="text-xs text-gray-700">{{ $amil->alamat }}</p>
                                                @if($amil->wilayah_tugas)
                                                    <div class="flex items-center text-xs">
                                                        <svg class="w-3 h-3 text-gray-400 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                                        </svg>
                                                        <span class="text-gray-700">Wilayah: {{ $amil->wilayah_tugas }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        {{-- Tombol Aksi --}}
                                        <div class="pt-3 border-t border-gray-200">
                                            <div class="flex gap-2">
                                                <a href="{{ route('amil.show', $amil->uuid) }}"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route('amil.edit', $amil->uuid) }}"
                                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($amils->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $amils->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    @if(request('search') || request('status') || request('masjid_id') || request('jenis_kelamin'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if(request('search'))
                                Tidak ada amil yang cocok dengan "{{ request('search') }}"
                            @else
                                Tidak ada amil yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('amil.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Amil</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan data amil untuk mengelola pengurus zakat.</p>
                        <a href="{{ route('amil.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Amil
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
                    Detail
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
                <button type="button" id="dropdown-toggle-status-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 transition-colors">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Ubah Status
                </button>
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

    {{-- Status Change Modal --}}
    <div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Ubah Status Amil</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-4 text-center">
                Pilih status baru untuk "<span id="modal-status-name" class="font-semibold text-gray-700"></span>"
            </p>
            <form id="status-form" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-3 mb-5">
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="status" value="aktif" class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm font-medium text-gray-900">Aktif</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="status" value="cuti" class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm font-medium text-gray-900">Cuti</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="status" value="nonaktif" class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm font-medium text-gray-900">Nonaktif</span>
                    </label>
                </div>
                <div class="flex justify-center gap-2 sm:gap-3">
                    <button type="button" id="cancel-status-btn"
                        class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-yellow-600 text-xs sm:text-sm font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Amil</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus amil
                "<span id="modal-amil-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">
                Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
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
        
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const showLink = document.getElementById('dropdown-show-link');
            const editLink = document.getElementById('dropdown-edit-link');
            const toggleStatusBtn = document.getElementById('dropdown-toggle-status-btn');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const tableContainer = document.getElementById('table-container');
            
            // Filter elements
            const filterStatus = document.getElementById('filter-status');
            const filterMasjid = document.getElementById('filter-masjid');
            const filterJenisKelamin = document.getElementById('filter-jenis-kelamin');
            const filterSortBy = document.getElementById('filter-sort-by');
            const filterForm = document.getElementById('filter-form');
            
            // Handle filter changes
            if (filterStatus) {
                filterStatus.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            if (filterMasjid) {
                filterMasjid.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            if (filterJenisKelamin) {
                filterJenisKelamin.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            if (filterSortBy) {
                filterSortBy.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
            
            // Desktop Expandable Rows
            document.querySelectorAll('.expandable-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a') || e.target.closest('.dropdown-toggle') || e.target.closest('button[type="submit"]')) return;
                    
                    const targetId = this.getAttribute('data-target');
                    const targetRow = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon');
                    
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
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a') || e.target.closest('.dropdown-toggle') || e.target.closest('button[type="submit"]')) return;
                    
                    const targetId = this.getAttribute('data-target');
                    const targetContent = document.getElementById(targetId);
                    const icon = this.querySelector('.expand-icon-mobile');
                    
                    if (targetContent.classList.contains('hidden')) {
                        targetContent.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        targetContent.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                });
            });
            
            // Dropdown toggle
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');
                if (toggle) {
                    e.stopPropagation();
                    const dropdownUuid = toggle.getAttribute('data-uuid');
                    const amilName = toggle.getAttribute('data-nama');
                    
                    if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }
                    
                    dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);
                    const rect = toggle.getBoundingClientRect();
                    
                    let top = rect.bottom;
                    let left = rect.left;
                    
                    const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
                    const dropdownHeight = 176;
                    
                    if (left + dropdownWidth > window.innerWidth) {
                        left = window.innerWidth - dropdownWidth - 10;
                    }
                    
                    if (top + dropdownHeight > window.innerHeight) {
                        top = rect.top - dropdownHeight;
                    }
                    
                    dropdownContainer.style.top = top + 'px';
                    dropdownContainer.style.left = left + 'px';
                    showLink.href = `/amil/${dropdownUuid}`;
                    editLink.href = `/amil/${dropdownUuid}/edit`;
                    
                    currentDropdownData = {
                        uuid: dropdownUuid,
                        name: amilName
                    };
                    
                    dropdownContainer.classList.remove('hidden');
                } else {
                    if (!dropdownContainer.contains(e.target)) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                    }
                }
            });
            
            // Toggle Status
            toggleStatusBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const uuid = currentDropdownData?.uuid;
                const name = currentDropdownData?.name;
                
                if (!uuid) return;
                
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
                
                const statusModal = document.getElementById('status-modal');
                const modalName = document.getElementById('modal-status-name');
                const statusForm = document.getElementById('status-form');
                
                modalName.textContent = name;
                statusForm.action = `/amil/${uuid}/toggle-status`;
                statusModal.classList.remove('hidden');
            });
            
            // Status form submit
            document.getElementById('status-form').addEventListener('submit', function(e) {
                e.preventDefault();
                this.submit();
            });
            
            document.getElementById('cancel-status-btn').addEventListener('click', function() {
                document.getElementById('status-modal').classList.add('hidden');
            });
            
            // Delete
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const uuid = currentDropdownData?.uuid;
                const name = currentDropdownData?.name;
                
                if (!uuid) return;
                
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
                
                const modalName = document.getElementById('modal-amil-name');
                const deleteForm = document.getElementById('delete-form');
                modalName.textContent = name;
                deleteForm.action = `/amil/${uuid}`;
                document.getElementById('delete-modal').classList.remove('hidden');
            });
            
            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });
            
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
            
            document.getElementById('status-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
            
            window.addEventListener('scroll', function() {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            }, true);
            
            if (tableContainer) {
                tableContainer.addEventListener('scroll', function() {
                    if (!dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                    }
                }, true);
            }
            
            window.addEventListener('resize', function() {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });
        });
        
        function toggleSearch() {
            const searchButton = document.getElementById('search-button');
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');
            
            if (searchForm.classList.contains('hidden')) {
                searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput.focus(), 50);
            } else {
                const hasQuery = '{{ request('search') }}' !== '';
                if (!hasQuery) {
                    searchInput.value = '';
                }
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        function toggleFilter() {
            const filterPanel = document.getElementById('filter-panel');
            filterPanel.classList.toggle('hidden');
        }
        
        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
@endpush