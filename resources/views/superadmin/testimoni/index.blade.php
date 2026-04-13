{{-- resources/views/superadmin/testimoni/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola Testimoni')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

            <!-- Header -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Kelola Testimoni</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi testimoni dari para muzakki</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button type="button" id="filterButton"
                            class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-white border border-green-500 hover:bg-green-50 text-green-600 text-sm font-medium rounded-lg transition-all
                            {{ request()->hasAny(['q', 'status', 'rating']) ? 'bg-green-50' : '' }}">
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
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($stats['total'] ?? 0) }}</span>
                        <span class="text-sm text-gray-500">Testimoni</span>
                    </div>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'status', 'rating']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.testimoni.index') }}" id="filter-form">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Testimoni</label>
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Cari nama atau isi testimoni..."
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Sudah Ditampilkan</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Disembunyikan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Rating</label>
                            <select name="rating"
                                class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                <option value="">Semua Rating</option>
                                @for ($r = 5; $r >= 1; $r--)
                                    <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Bintang</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if(request()->hasAny(['q', 'status', 'rating']))
                            <a href="{{ route('superadmin.testimoni.index') }}"
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
            @if(request()->hasAny(['q', 'status', 'rating']))
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
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Status: {{ request('status') == 'pending' ? 'Menunggu' : (request('status') == 'approved' ? 'Tampil' : 'Disembunyikan') }}
                                <button onclick="removeFilter('status')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                        @if(request('rating'))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-700 text-xs rounded-lg border border-green-200">
                                Rating: {{ request('rating') }} ★
                                <button onclick="removeFilter('rating')" class="hover:text-green-900 ml-1">×</button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($testimonis->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TESTIMONI</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($testimonis as $t)
                                @php
                                    $isPending = !$t->is_approved;
                                    $isApproved = $t->is_approved;
                                    if ($isApproved) {
                                        $statusCls = 'bg-green-50 text-green-700 border border-green-200';
                                        $statusLbl = 'Tampil';
                                    } else {
                                        $statusCls = 'bg-amber-50 text-amber-700 border border-amber-200';
                                        $statusLbl = 'Menunggu';
                                    }
                                @endphp

                                <!-- Baris Utama -->
                                <tr class="group hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-300 cursor-pointer expandable-row {{ $isPending ? 'bg-amber-50/30' : '' }}"
                                    data-target="detail-{{ $t->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon inline-block"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar -->
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 border border-green-200">
                                                <span class="text-sm font-semibold text-green-700">
                                                    {{ strtoupper(substr($t->nama_pengirim, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-sm font-medium text-gray-800 group-hover:text-green-700 transition-colors duration-200">
                                                    {{ $t->nama_pengirim }}
                                                </span>
                                                @if($t->pekerjaan)
                                                    <div class="text-xs text-gray-400 mt-0.5">{{ $t->pekerjaan }}</div>
                                                @else
                                                    <div class="text-xs text-gray-400 mt-0.5">Klik untuk detail</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusCls }}">
                                            {{ $statusLbl }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-800">{{ $t->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $t->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Setujui / Sembunyikan -->
                                            @if(!$t->is_approved)
                                                <div class="relative group/tooltip">
                                                    <form action="{{ route('superadmin.testimoni.approve', $t->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            onclick="return confirm('Tampilkan testimoni ini di landing page?')"
                                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Tampilkan
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="relative group/tooltip">
                                                    <form action="{{ route('superadmin.testimoni.reject', $t->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            onclick="return confirm('Sembunyikan testimoni ini?')"
                                                            class="flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                        Sembunyikan
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Hapus -->
                                            <div class="relative group/tooltip">
                                                <form action="{{ route('superadmin.testimoni.destroy', $t->id) }}" method="POST" class="inline"
                                                    onsubmit="return confirm('Hapus testimoni ini secara permanen?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-0.5 bg-gray-800 text-white text-xs rounded whitespace-nowrap opacity-0 invisible group-hover/tooltip:opacity-100 group-hover/tooltip:visible transition-all duration-200 pointer-events-none z-10">
                                                    Hapus
                                                    <div class="absolute top-full left-1/2 -translate-x-1/2 -mt-0.5 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Expandable Row Desktop -->
                                <tr id="detail-{{ $t->id }}" class="hidden border-b border-gray-100 expandable-content">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="4" class="px-6 py-4 bg-gray-50/30">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Kolom 1: Data Pengirim -->
                                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Pengirim</h4>
                                                <div class="space-y-2.5">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Nama</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ $t->nama_pengirim }}</span>
                                                    </div>
                                                    @if($t->pekerjaan)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Pekerjaan</span>
                                                            <span class="text-xs font-medium text-gray-700">{{ $t->pekerjaan }}</span>
                                                        </div>
                                                    @endif
                                                    @if($t->muzakki)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-xs text-gray-500">Email</span>
                                                            <span class="text-xs font-medium text-gray-700">{{ $t->muzakki->pengguna->email ?? '-' }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex justify-between items-center pt-1 border-t border-gray-100">
                                                        <span class="text-xs text-gray-500">Rating</span>
                                                        <span class="text-xs font-medium text-gray-700 flex items-center gap-0.5">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-3 h-3 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @endfor
                                                            <span class="ml-1">{{ $t->rating }}/5</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Kolom 2: Isi Testimoni -->
                                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Isi Testimoni</h4>
                                                <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                                    <p class="text-xs text-gray-600 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                                </div>
                                            </div>

                                            <!-- Kolom 3: Riwayat -->
                                            <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Riwayat</h4>
                                                <div class="space-y-2.5 text-xs">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-500">Dikirim</span>
                                                        <span class="font-medium text-gray-700">{{ $t->created_at->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                    @if($t->is_approved && $t->approvedBy)
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-gray-500">Disetujui oleh</span>
                                                            <span class="font-medium text-green-700">{{ $t->approvedBy->username }}</span>
                                                        </div>
                                                        @if($t->approved_at)
                                                            <div class="flex justify-between items-center">
                                                                <span class="text-gray-500">Tgl Setujui</span>
                                                                <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($t->approved_at)->format('d/m/Y H:i') }}</span>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="flex justify-between items-center">
                                                            <span class="text-gray-500">Status</span>
                                                            <span class="font-medium text-amber-600">Belum disetujui</span>
                                                        </div>
                                                    @endif
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
                    @foreach($testimonis as $t)
                        @php
                            $isPending = !$t->is_approved;
                            $isApproved = $t->is_approved;
                            if ($isApproved) {
                                $statusCls = 'bg-green-50 text-green-700 border border-green-200';
                                $statusLbl = 'Tampil';
                            } else {
                                $statusCls = 'bg-amber-50 text-amber-700 border border-amber-200';
                                $statusLbl = 'Menunggu';
                            }
                        @endphp
                        <div class="p-4 hover:bg-gradient-to-r hover:from-green-50/20 hover:to-transparent transition-all duration-200 {{ $isPending ? 'bg-amber-50/30' : '' }}">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $t->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Testimoni</span>
                                        </div>
                                        <h3 class="text-sm font-semibold text-gray-800">{{ $t->nama_pengirim }}</h3>
                                        @if($t->pekerjaan)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $t->pekerjaan }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusCls }}">
                                                {{ $statusLbl }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $t->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <!-- Setujui / Sembunyikan -->
                                        @if(!$t->is_approved)
                                            <div class="relative group/tooltip">
                                                <form action="{{ route('superadmin.testimoni.approve', $t->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return confirm('Tampilkan testimoni ini?')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="relative group/tooltip">
                                                <form action="{{ route('superadmin.testimoni.reject', $t->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return confirm('Sembunyikan testimoni ini?')"
                                                        class="flex items-center justify-center p-1.5 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- Hapus -->
                                        <div class="relative group/tooltip">
                                            <form action="{{ route('superadmin.testimoni.destroy', $t->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Hapus testimoni ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center justify-center p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Expandable Detail -->
                            <div id="detail-mobile-{{ $t->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    <!-- Data Pengirim -->
                                    <div class="bg-white rounded-xl border border-gray-200 p-3">
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2.5">Data Pengirim</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Nama</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $t->nama_pengirim }}</span>
                                            </div>
                                            @if($t->pekerjaan)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Pekerjaan</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ $t->pekerjaan }}</span>
                                                </div>
                                            @endif
                                            @if($t->muzakki)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Email</span>
                                                    <span class="text-xs font-medium text-gray-700">{{ $t->muzakki->pengguna->email ?? '-' }}</span>
                                                </div>
                                            @endif
                                            <div class="flex justify-between items-center pt-1.5 border-t border-gray-100">
                                                <span class="text-xs text-gray-500">Rating</span>
                                                <span class="text-xs font-medium text-gray-700 flex items-center gap-0.5">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3 h-3 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                        </svg>
                                                    @endfor
                                                    <span class="ml-1">{{ $t->rating }}/5</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Isi Testimoni -->
                                    <div class="bg-white rounded-xl border border-gray-200 p-3">
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2.5">Isi Testimoni</h4>
                                        <div class="bg-gray-50 rounded-lg p-3 border border-gray-100">
                                            <p class="text-xs text-gray-600 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                        </div>
                                    </div>

                                    <!-- Riwayat -->
                                    <div class="bg-white rounded-xl border border-gray-200 p-3">
                                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2.5">Riwayat</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-500">Dikirim</span>
                                                <span class="text-xs font-medium text-gray-700">{{ $t->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            @if($t->is_approved && $t->approvedBy)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Disetujui oleh</span>
                                                    <span class="text-xs font-medium text-green-700">{{ $t->approvedBy->username }}</span>
                                                </div>
                                                @if($t->approved_at)
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-xs text-gray-500">Tgl Setujui</span>
                                                        <span class="text-xs font-medium text-gray-700">{{ \Carbon\Carbon::parse($t->approved_at)->format('d/m/Y H:i') }}</span>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-gray-500">Status</span>
                                                    <span class="text-xs font-medium text-amber-600">Belum disetujui</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($testimonis->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50/30 to-white">
                        {{ $testimonis->withQueryString()->links() }}
                    </div>
                @endif

            @else
                <!-- Empty State -->
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    @if(request('q') || request('status') || request('rating'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <button onclick="resetAllFilters()" class="text-sm text-green-600 hover:text-green-700 font-medium transition-colors">
                            Reset filter
                        </button>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada testimoni</p>
                        <p class="text-xs text-gray-400">Testimoni akan muncul setelah muzakki mengirimkannya</p>
                    @endif
                </div>
            @endif
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
    });

    function removeFilter(filterName) {
        const url = new URL(window.location.href);
        url.searchParams.delete(filterName);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    function resetAllFilters() {
        const url = new URL(window.location.href);
        ['q', 'status', 'rating'].forEach(f => url.searchParams.delete(f));
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    }

    function toggleFilter() {
        const filterPanel = document.getElementById('filterPanel');
        if (filterPanel) filterPanel.classList.add('hidden');
    }
</script>
@endpush