{{-- resources/views/admin-masjid/setor-kas/riwayat.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Setoran Kas')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- ── Header ── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Riwayat Setoran Kas</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $setorans->total() }} Setoran</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                    {{-- Tombol ke Pending --}}
                    <a href="{{ route('admin-masjid.setor-kas.pending') }}"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-800 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Pending</span>
                    </a>

                    {{-- Filter --}}
                    <button type="button" onclick="toggleFilter()"
                        class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                        {{ request()->hasAny(['status', 'dari', 'sampai']) ? 'ring-2 ring-primary' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Filter</span>
                    </button>

                    {{-- Search --}}
                    <div id="search-container" class="transition-all duration-300"
                        style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                        <button type="button" onclick="toggleSearch()" id="search-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                        </button>
                        <form method="GET" action="{{ route('admin-masjid.setor-kas.riwayat') }}" id="search-form"
                            class="{{ request('q') ? '' : 'hidden' }}">
                            @foreach(['status','dari','sampai'] as $f)
                                @if(request($f))
                                    <input type="hidden" name="{{ $f }}" value="{{ request($f) }}">
                                @endif
                            @endforeach
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="search" name="q" value="{{ request('q') }}"
                                        id="search-input" placeholder="Cari no. setor / amil..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                </div>
                                @if(request()->hasAny(['q','status','dari','sampai']))
                                    <a href="{{ route('admin-masjid.setor-kas.riwayat') }}"
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

        {{-- ── Filter Panel ── --}}
        <div id="filter-panel"
            class="{{ request()->hasAny(['status','dari','sampai']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('admin-masjid.setor-kas.riwayat') }}" id="filter-form">
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="ditolak"  {{ request('status') == 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari" value="{{ request('dari') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            onchange="this.form.submit()">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai" value="{{ request('sampai') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                            onchange="this.form.submit()">
                    </div>
                </div>
                @if(request()->hasAny(['status','dari','sampai']))
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('admin-masjid.setor-kas.riwayat', request('q') ? ['q' => request('q')] : []) }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-800 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>

        @if($setorans->count() > 0)

            {{-- ── Summary Strip ── --}}
            @php
                $sPending  = $summary['pending']  ?? null;
                $sDiterima = $summary['diterima'] ?? null;
                $sDitolak  = $summary['ditolak']  ?? null;
            @endphp

            {{-- ── Desktop Table ── --}}
            <div class="hidden md:block overflow-x-auto" id="table-container">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($setorans as $setor)
                            @php
                                $selisih = null;
                                if ($setor->jumlah_dihitung_fisik !== null) {
                                    $selisih = $setor->jumlah_dihitung_fisik - $setor->jumlah_disetor;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                {{-- Amil --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}
                                    </div>
                                    <div class="text-xs text-gray-500 font-mono mt-0.5">{{ $setor->no_setor }}</div>
                                    <div class="text-xs text-gray-400">{{ $setor->tanggal_setor->format('d M Y') }}</div>
                                </td>
                                {{-- Periode --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $setor->periode_dari->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">s/d {{ $setor->periode_sampai->format('d M Y') }}</div>
                                </td>
                                {{-- Jumlah --}}
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $setor->jumlah_disetor_formatted }}</div>
                                    @if($setor->jumlah_dihitung_fisik !== null)
                                        <div class="text-xs text-gray-400">
                                            Fisik: Rp {{ number_format($setor->jumlah_dihitung_fisik, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>
                                {{-- Selisih --}}
                                <td class="px-6 py-4">
                                    @if($selisih !== null)
                                        @if($selisih == 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Cocok</span>
                                        @elseif($selisih > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                +Rp {{ number_format($selisih, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                -Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($setor->status === 'diterima')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Diterima
                                        </span>
                                        @if($setor->diterima_at)
                                            <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($setor->diterima_at)->format('d M Y, H:i') }}</div>
                                        @endif
                                    @elseif($setor->status === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Ditolak
                                        </span>
                                        @if($setor->ditolak_at)
                                            <div class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::parse($setor->ditolak_at)->format('d M Y, H:i') }}</div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                {{-- Aksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button type="button"
                                        data-uuid="{{ $setor->uuid }}"
                                        data-no="{{ $setor->no_setor }}"
                                        data-nama="{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}"
                                        data-status="{{ $setor->status }}"
                                        data-alasan="{{ $setor->alasan_penolakan ?? '' }}"
                                        class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ── Mobile Cards ── --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($setorans as $setor)
                    @php
                        $selisih = null;
                        if ($setor->jumlah_dihitung_fisik !== null) {
                            $selisih = $setor->jumlah_dihitung_fisik - $setor->jumlah_disetor;
                        }
                    @endphp
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 mb-0.5">
                                    {{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}
                                </h3>
                                <p class="text-xs text-gray-400 font-mono mb-2">{{ $setor->no_setor }}</p>

                                {{-- Status badge --}}
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @if($setor->status === 'diterima')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Diterima
                                        </span>
                                    @elseif($setor->status === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                            Pending
                                        </span>
                                    @endif
                                    @if($selisih !== null)
                                        @if($selisih == 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Cocok</span>
                                        @elseif($selisih > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">+Rp {{ number_format($selisih, 0, ',', '.') }}</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">-Rp {{ number_format(abs($selisih), 0, ',', '.') }}</span>
                                        @endif
                                    @endif
                                </div>

                                <div class="space-y-1.5">
                                    <div class="flex items-center text-xs text-gray-600">
                                        <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Periode: {{ $setor->periode_dari->format('d M Y') }} — {{ $setor->periode_sampai->format('d M Y') }}
                                    </div>
                                    <div class="flex items-center text-xs text-gray-600">
                                        <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="font-semibold text-gray-800">{{ $setor->jumlah_disetor_formatted }}</span>
                                        @if($setor->jumlah_dihitung_fisik !== null)
                                            <span class="ml-1 text-gray-400">(Fisik: Rp {{ number_format($setor->jumlah_dihitung_fisik, 0, ',', '.') }})</span>
                                        @endif
                                    </div>
                                    @if($setor->status === 'ditolak' && $setor->alasan_penolakan)
                                        <div class="text-xs text-red-600 bg-red-50 rounded-lg px-2 py-1.5 mt-1">
                                            <span class="font-medium">Alasan:</span> {{ $setor->alasan_penolakan }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <button type="button"
                                data-uuid="{{ $setor->uuid }}"
                                data-no="{{ $setor->no_setor }}"
                                data-nama="{{ $setor->amil->nama_lengkap ?? $setor->amil->pengguna->username ?? '-' }}"
                                data-status="{{ $setor->status }}"
                                data-alasan="{{ $setor->alasan_penolakan ?? '' }}"
                                class="dropdown-toggle flex-shrink-0 ml-2 inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($setorans->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $setorans->links() }}
                </div>
            @endif

        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                @if(request()->hasAny(['q','status','dari','sampai']))
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-6">Tidak ada setoran yang sesuai dengan filter yang dipilih</p>
                    <a href="{{ route('admin-masjid.setor-kas.riwayat') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Reset Pencarian
                    </a>
                @else
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Setoran</h3>
                    <p class="text-sm text-gray-500">Semua setoran kas dari amil akan tampil di sini.</p>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- ── Dropdown Container ── --}}
<div id="dropdown-container" class="fixed hidden z-50">
    <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            <a href="#" id="dropdown-detail-link"
                class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Detail
            </a>
            <button type="button" id="dropdown-alasan-btn"
                class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Lihat Alasan Tolak
            </button>
        </div>
    </div>
</div>

{{-- ── Modal: Alasan Penolakan ── --}}
<div id="alasan-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
        <div class="flex justify-center mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <h3 class="text-base font-semibold text-gray-900 text-center mb-1">Alasan Penolakan</h3>
        <p class="text-xs text-gray-400 text-center mb-4" id="modal-alasan-no"></p>
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-5">
            <p class="text-sm text-red-700" id="modal-alasan-text"></p>
        </div>
        <div class="flex justify-center">
            <button type="button" onclick="document.getElementById('alasan-modal').classList.add('hidden'); document.body.style.overflow='';"
                class="px-6 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownContainer = document.getElementById('dropdown-container');
    const detailLink        = document.getElementById('dropdown-detail-link');
    const alasanBtn         = document.getElementById('dropdown-alasan-btn');
    const tableContainer    = document.getElementById('table-container');

    let currentUuid   = null;
    let currentAlasan = null;
    let currentNo     = null;

    // ── Dropdown ─────────────────────────────────────────────────
    document.addEventListener('click', function (e) {
        const toggle = e.target.closest('.dropdown-toggle');

        if (toggle) {
            e.stopPropagation();

            const uuid   = toggle.dataset.uuid;
            const no     = toggle.dataset.no;
            const status = toggle.dataset.status;
            const alasan = toggle.dataset.alasan;

            if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                !dropdownContainer.classList.contains('hidden')) {
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');
                return;
            }

            currentUuid   = uuid;
            currentNo     = no;
            currentAlasan = alasan;
            dropdownContainer.setAttribute('data-current-uuid', uuid);

            // Posisi
            const rect      = toggle.getBoundingClientRect();
            const dropdownW = window.innerWidth < 640 ? 176 : 192;
            const dropdownH = 100;
            let top  = rect.bottom + window.scrollY + 6;
            let left = rect.left   + window.scrollX;

            if (rect.left + dropdownW > window.innerWidth) {
                left = window.innerWidth - dropdownW - 10 + window.scrollX;
            }
            if (rect.bottom + dropdownH > window.innerHeight) {
                top = rect.top - dropdownH + window.scrollY - 6;
            }

            dropdownContainer.style.top  = top  + 'px';
            dropdownContainer.style.left = left + 'px';

            detailLink.href = `/admin-setor-kas/${uuid}`;

            // Tombol alasan — hanya tampil jika status ditolak dan ada alasan
            if (status === 'ditolak' && alasan) {
                alasanBtn.classList.remove('hidden');
            } else {
                alasanBtn.classList.add('hidden');
            }

            dropdownContainer.classList.remove('hidden');

        } else if (!dropdownContainer.contains(e.target)) {
            dropdownContainer.classList.add('hidden');
            dropdownContainer.removeAttribute('data-current-uuid');
        }
    });

    // ── Lihat Alasan ─────────────────────────────────────────────
    alasanBtn.addEventListener('click', function () {
        dropdownContainer.classList.add('hidden');
        dropdownContainer.removeAttribute('data-current-uuid');

        document.getElementById('modal-alasan-no').textContent   = currentNo;
        document.getElementById('modal-alasan-text').textContent  = currentAlasan || 'Tidak ada keterangan.';
        document.getElementById('alasan-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    document.getElementById('alasan-modal').addEventListener('click', function (e) {
        if (e.target === this) {
            this.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // ── Tutup saat scroll/resize ──────────────────────────────────
    const closeDropdown = () => {
        dropdownContainer.classList.add('hidden');
        dropdownContainer.removeAttribute('data-current-uuid');
    };
    window.addEventListener('scroll', closeDropdown, true);
    window.addEventListener('resize', closeDropdown);
    if (tableContainer) tableContainer.addEventListener('scroll', closeDropdown, true);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeDropdown();
            document.getElementById('alasan-modal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    });
});

// ── Search & Filter ───────────────────────────────────────────────
function toggleSearch() {
    const btn       = document.getElementById('search-button');
    const form      = document.getElementById('search-form');
    const input     = document.getElementById('search-input');
    const container = document.getElementById('search-container');
    if (form.classList.contains('hidden')) {
        btn.classList.add('hidden');
        form.classList.remove('hidden');
        container.style.minWidth = '280px';
        setTimeout(() => input.focus(), 50);
    } else {
        form.classList.add('hidden');
        btn.classList.remove('hidden');
        container.style.minWidth = '';
    }
}

function toggleFilter() {
    document.getElementById('filter-panel').classList.toggle('hidden');
}
</script>
@endpush