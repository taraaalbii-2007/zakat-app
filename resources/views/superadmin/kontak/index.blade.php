{{-- resources/views/superadmin/kontak/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Pesan Masuk</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola pesan dari pengguna dan pengunjung</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'status']) ? 'bg-green-50' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter & Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistik Bar -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $kontaks->total() }}</span>
                        <span class="text-sm text-gray-500">Pesan</span>
                    </div>
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <span class="text-xs text-gray-500">Baru:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $totalBaru }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                            <span class="text-xs text-gray-500">Dibaca:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $totalDibaca }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'status']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.kontak.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Pesan</label>
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari nama, email, subjek..."
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="baru"    {{ request('status') === 'baru'    ? 'selected' : '' }}>Baru</option>
                                <option value="dibaca"  {{ request('status') === 'dibaca'  ? 'selected' : '' }}>Dibaca</option>
                                <option value="dibalas" {{ request('status') === 'dibalas' ? 'selected' : '' }}>Dibalas</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if(request()->hasAny(['q', 'status']))
                            <a href="{{ route('superadmin.kontak.index') }}"
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

            <!-- Active Filter Tags -->
            @if(request()->hasAny(['q', 'status']))
                <div class="px-5 py-2.5 border-b border-gray-100">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-gray-400">Filter aktif:</span>
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
                            @php $statusMap = ['baru' => 'Baru', 'dibaca' => 'Dibaca', 'dibalas' => 'Dibalas']; @endphp
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ $statusMap[request('status')] ?? request('status') }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($kontaks->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">PENGIRIM</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SUBJEK</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($kontaks as $kontak)
                                @php $isUnread = is_null($kontak->dibaca_at); @endphp

                                <!-- Baris Utama -->
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300 cursor-pointer expandable-row {{ $isUnread ? 'bg-amber-50/30' : '' }}"
                                    data-target="detail-{{ $kontak->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 border border-green-200">
                                                <span class="text-sm font-semibold text-green-700">
                                                    {{ strtoupper(substr($kontak->nama, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors {{ $isUnread ? 'font-semibold' : '' }}">
                                                    {{ $kontak->nama }}
                                                </span>
                                                <div class="text-xs text-gray-400 mt-0.5">{{ $kontak->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-800 {{ $isUnread ? 'font-semibold' : '' }} truncate max-w-xs">{{ $kontak->subjek }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">Klik untuk detail</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        {!! $kontak->status_badge !!}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800">{{ $kontak->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $kontak->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Buka -->
                                            <div class="relative group/tooltip">
                                                <a href="{{ route('superadmin.kontak.show', $kontak) }}"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Buka Pesan
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                            <!-- Hapus -->
                                            <div class="relative group/tooltip">
                                                <button type="button"
                                                    class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200"
                                                    data-uuid="{{ $kontak->id }}" data-nama="{{ addslashes($kontak->nama) }}">
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

                                <!-- Expandable Row Desktop -->
                                <tr id="detail-{{ $kontak->id }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="5" class="px-6 py-4 bg-gray-50/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Kolom 1: Data Pengirim -->
                                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Pengirim</h4>
                                                <div class="space-y-2.5">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Nama</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $kontak->nama }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Email</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $kontak->email }}</span>
                                                    </div>
                                                    @if($kontak->telepon ?? false)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Telepon</span>
                                                            <span class="text-xs font-medium text-gray-700">{{ $kontak->telepon }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex justify-between items-center pt-1.5 border-t border-gray-100">
                                                        <span class="text-xs text-gray-500">Status</span>
                                                        {!! $kontak->status_badge !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kolom 2: Isi Pesan -->
                                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Isi Pesan</h4>
                                                <p class="text-xs font-semibold text-gray-700 mb-2">{{ $kontak->subjek }}</p>
                                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                                    <p class="text-xs text-gray-600 leading-relaxed">{{ Str::limit($kontak->pesan, 200) }}</p>
                                                </div>
                                            </div>

                                            <!-- Kolom 3: Riwayat -->
                                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Riwayat</h4>
                                                <div class="space-y-2.5 text-xs">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500">Dikirim</span>
                                                        <span class="font-medium text-gray-700">{{ $kontak->created_at->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    @if($kontak->dibaca_at)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-gray-500">Dibaca</span>
                                                            <span class="font-medium text-blue-700">{{ \Carbon\Carbon::parse($kontak->dibaca_at)->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @else
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-gray-500">Dibaca</span>
                                                            <span class="font-medium text-amber-600">Belum dibaca</span>
                                                        </div>
                                                    @endif
                                                    @if($kontak->dibalas_at ?? false)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-gray-500">Dibalas</span>
                                                            <span class="font-medium text-green-700">{{ \Carbon\Carbon::parse($kontak->dibalas_at)->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="mt-4 pt-3 border-t border-gray-100">
                                                    <a href="{{ route('superadmin.kontak.show', $kontak) }}"
                                                        class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-all">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Buka & Balas
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

                <!-- MOBILE CARD VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach($kontaks as $kontak)
                        @php $isUnread = is_null($kontak->dibaca_at); @endphp
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 {{ $isUnread ? 'bg-amber-50/30' : '' }}">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $kontak->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Pesan</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800 {{ $isUnread ? 'font-bold' : '' }}">{{ $kontak->nama }}</h3>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $kontak->email }}</p>
                                        <p class="text-xs text-gray-600 mt-1 font-medium truncate">{{ $kontak->subjek }}</p>
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            {!! $kontak->status_badge !!}
                                            <span class="text-xs text-gray-400">{{ $kontak->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Buka -->
                                        <div class="relative group/tooltip">
                                            <a href="{{ route('superadmin.kontak.show', $kontak) }}"
                                                class="flex items-center justify-center p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                        <!-- Hapus -->
                                        <div class="relative group/tooltip">
                                            <button type="button"
                                                class="delete-btn flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                data-uuid="{{ $kontak->id }}" data-nama="{{ addslashes($kontak->nama) }}">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $kontak->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <!-- Data Pengirim -->
                                    <div class="bg-white rounded-xl border border-gray-200 p-3">
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2.5">Data Pengirim</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Nama</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $kontak->nama }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Email</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $kontak->email }}</span>
                                            </div>
                                            @if($kontak->telepon ?? false)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Telepon</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ $kontak->telepon }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Isi Pesan -->
                                    <div class="bg-white rounded-xl border border-gray-200 p-3">
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2.5">Isi Pesan</h4>
                                        <p class="text-xs font-semibold text-gray-700 mb-2">{{ $kontak->subjek }}</p>
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                            <p class="text-xs text-gray-600 leading-relaxed">{{ Str::limit($kontak->pesan, 150) }}</p>
                                        </div>
                                    </div>

                                    <!-- Riwayat & Aksi -->
                                    <div class="bg-white rounded-xl border border-gray-200 p-3">
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2.5">Riwayat</h4>
                                        <div class="space-y-2 mb-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Dikirim</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $kontak->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            @if($kontak->dibaca_at)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Dibaca</span>
                                                    <span class="text-xs font-medium text-blue-700">{{ \Carbon\Carbon::parse($kontak->dibaca_at)->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @else
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Dibaca</span>
                                                    <span class="text-xs font-medium text-amber-600">Belum dibaca</span>
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ route('superadmin.kontak.show', $kontak) }}"
                                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Buka & Balas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($kontaks->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $kontaks->withQueryString()->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @if(request('q') || request('status'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada pesan yang sesuai dengan filter</p>
                        <button onclick="resetAllFilters()" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset filter
                        </button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada pesan masuk</p>
                        <p class="text-xs text-gray-400">Pesan dari pengguna akan muncul di sini</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl transform transition-all duration-300">
            <div class="p-6">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Pesan</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Apakah Anda yakin ingin menghapus pesan dari "<span id="modal-nama" class="font-semibold text-gray-700"></span>"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3">
                    <button type="button" id="cancel-delete"
                        class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200">
                        Batal
                    </button>
                    <button type="button" id="confirm-delete"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 rounded-xl text-sm font-medium text-white transition-all duration-200 shadow-md hover:shadow-lg active:scale-95">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filterButton');
        const filterPanel = document.getElementById('filterPanel');

        if (filterButton && filterPanel) {
            filterButton.addEventListener('click', function() {
                filterPanel.classList.toggle('hidden');
            });
        }

        // Desktop Expandable row
        document.querySelectorAll('.expandable-row').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) return;
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
                if (e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) return;
                const targetId = this.getAttribute('data-target');
                const targetContent = document.getElementById(targetId);
                const icon = this.querySelector('.expand-icon-mobile');
                if (targetContent) {
                    targetContent.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                }
            });
        });

        // Delete modal
        let currentUuid = null;
        const deleteModal = document.getElementById('delete-modal');

        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                currentUuid = this.dataset.uuid;
                document.getElementById('modal-nama').textContent = this.dataset.nama;
                deleteModal.classList.remove('hidden');
            });
        });

        document.getElementById('cancel-delete').addEventListener('click', function() {
            deleteModal.classList.add('hidden');
        });

        document.getElementById('confirm-delete').addEventListener('click', function() {
            if (!currentUuid) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/superadmin-kontak/${currentUuid}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        });

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) deleteModal.classList.add('hidden');
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
        ['q', 'status'].forEach(f => url.searchParams.delete(f));
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    function toggleFilter() {
        const filterPanel = document.getElementById('filterPanel');
        if (filterPanel) filterPanel.classList.add('hidden');
    }
</script>
@endpush