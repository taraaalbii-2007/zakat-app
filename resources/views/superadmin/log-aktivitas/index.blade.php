@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

      <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Log Aktivitas Sistem</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Rekam jejak aktivitas pengguna dalam sistem</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']) ? 'bg-green-50' : '' }}">
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
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($logs->total()) }}</span>
                        <span class="text-sm text-gray-500">Aktivitas</span>
                    </div>

                    <!-- Filter Tabs Desktop -->
                    <div class="hidden md:flex items-center gap-1.5 p-1 bg-gray-100 rounded-lg">
                        <a href="{{ route('log-aktivitas.index') }}" 
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ !request('aktivitas') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Semua
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'login']) }}" 
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ request('aktivitas') == 'login' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Login
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'create']) }}" 
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ request('aktivitas') == 'create' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Tambah
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'update']) }}" 
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ request('aktivitas') == 'update' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Edit
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'delete']) }}" 
                            class="px-3 py-1.5 text-xs font-medium rounded-md transition-all {{ request('aktivitas') == 'delete' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Hapus
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel" class="{{ request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('log-aktivitas.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Pencarian</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari aktivitas..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Aktivitas -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Aktivitas</label>
                                <select name="aktivitas"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Aktivitas</option>
                                    @foreach($aktivitasList as $akt)
                                        <option value="{{ $akt }}" {{ request('aktivitas') == $akt ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $akt)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Modul -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Modul</label>
                                <select name="modul"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Modul</option>
                                    @foreach($modulList as $mod)
                                        <option value="{{ $mod }}" {{ request('modul') == $mod ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $mod)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Peran -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Peran</label>
                                <select name="peran"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Peran</option>
                                    @foreach($peranList as $per)
                                        <option value="{{ $per }}" {{ request('peran') == $per ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $per)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Tanggal -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal</label>
                                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if(request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']))
                            <a href="{{ route('log-aktivitas.index') }}"
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
            @if(request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']))
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
                        @if(request('aktivitas'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Aktivitas: {{ request('aktivitas') }}
                                <button onclick="removeFilter('aktivitas')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('modul'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Modul: {{ request('modul') }}
                                <button onclick="removeFilter('modul')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('peran'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Peran: {{ request('peran') }}
                                <button onclick="removeFilter('peran')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('tanggal'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Tanggal: {{ request('tanggal') }}
                                <button onclick="removeFilter('tanggal')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($logs->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 w-12">NO</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">PENGGUNA</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">AKTIVITAS</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden lg:table-cell">MODUL</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">DESKRIPSI</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">WAKTU</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 w-16">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($logs as $index => $log)
                                <tr class="hover:bg-green-50/20 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="text-xs text-gray-500">{{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $log->nama_pengguna }}</p>
                                            @if($log->email_pengguna)
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $log->email_pengguna }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $log->peran) }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $log->badge_color }} capitalize">
                                            <span class="w-1.5 h-1.5 rounded-full {{ strpos($log->badge_color, 'green') !== false ? 'bg-green-500' : (strpos($log->badge_color, 'blue') !== false ? 'bg-blue-500' : (strpos($log->badge_color, 'red') !== false ? 'bg-red-500' : 'bg-gray-500')) }} mr-1"></span>
                                            {{ str_replace('_', ' ', $log->aktivitas) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell">
                                        <span class="text-xs text-gray-700 capitalize">{{ str_replace('_', ' ', $log->modul) }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit($log->deskripsi ?? '-', 60) }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="text-xs text-gray-700">{{ $log->created_at->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('log-aktivitas.show', $log->uuid) }}" 
                                            class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-green-600 hover:bg-green-50 transition-colors"
                                            title="Lihat Detail">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE VIEW -->
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($logs as $log)
                        <div class="p-4 hover:bg-green-50/20 transition-colors">
                            <div class="flex items-start justify-between gap-3 mb-2">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <p class="text-sm font-semibold text-gray-900">{{ $log->nama_pengguna }}</p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $log->badge_color }} capitalize">
                                            {{ str_replace('_', ' ', $log->aktivitas) }}
                                        </span>
                                    </div>
                                    @if($log->email_pengguna)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $log->email_pengguna }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $log->peran) }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs text-gray-700">{{ $log->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs text-gray-500 capitalize">Modul:</span>
                                <span class="text-xs text-gray-700 capitalize">{{ str_replace('_', ' ', $log->modul) }}</span>
                            </div>

                            <p class="text-xs text-gray-600">{{ $log->deskripsi ?? 'Tidak ada deskripsi' }}</p>

                            <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                                @if($log->ip_address)
                                    <span class="text-xs font-mono text-gray-400">{{ $log->ip_address }}</span>
                                @endif
                                <a href="{{ route('log-aktivitas.show', $log->uuid) }}" 
                                    class="inline-flex items-center gap-1 text-xs font-medium text-green-600 hover:text-green-700">
                                    Detail
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($logs->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $logs->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @if(request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('log-aktivitas.index') }}" class="text-sm text-green-600 hover:text-green-700">Reset semua filter</a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada aktivitas</p>
                        <p class="text-xs text-gray-400">Log aktivitas sistem akan muncul di sini</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterPanel = document.getElementById('filterPanel');
    const closeBtn = document.getElementById('closeFilterPanelBtn');
    
    if (filterButton && filterPanel) {
        filterButton.addEventListener('click', () => filterPanel.classList.toggle('hidden'));
    }
    if (closeBtn && filterPanel) {
        closeBtn.addEventListener('click', () => filterPanel.classList.add('hidden'));
    }
});

function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}
</script>
@endpush