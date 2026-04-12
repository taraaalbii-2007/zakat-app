{{-- resources/views/superadmin/testimoni/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola Testimoni')

@section('content')
    <div class="space-y-6">
        <!-- Container utama -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl overflow-hidden transition-all duration-300">

   <!-- Header - DIPERBAIKI -->
            <div class="px-5 py-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-base font-semibold text-gray-800">Kelola Testimoni</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola dan konfigurasi testimoni dari para muzakki</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Tombol Filter - DIPERBAIKI -->
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

            <!-- Statistik Bar - DIPERBAIKI -->
            <div class="px-5 py-3 bg-gradient-to-r from-green-50/20 to-transparent border-b border-gray-100">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Total:</span>
                        <span class="text-sm font-semibold text-gray-800">{{ number_format($stats['total'] ?? 0) }}</span>
                        <span class="text-sm text-gray-500">Testimoni</span>
                    </div>

                    <!-- Stats Ringkasan Desktop -->
                    <div class="hidden md:flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                            <span class="text-xs text-gray-500">Menunggu:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['pending'] ?? 0) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-500">Tampil:</span>
                            <span class="text-xs font-semibold text-gray-700">{{ number_format($stats['approved'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Panel - DIPERBAIKI -->
            <div id="filterPanel" class="{{ request()->hasAny(['q', 'status', 'rating']) ? '' : 'hidden' }} px-5 py-3 border-b border-gray-100 bg-green-50/30">
                <form method="GET" action="{{ route('superadmin.testimoni.index') }}" id="filter-form">
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Search Field -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Testimoni</label>
                                <div class="relative">
                                    <input type="text" name="q" value="{{ request('q') }}"
                                        placeholder="Cari nama pengirim atau isi testimoni..."
                                        class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white pl-8">
                                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Filter Status -->
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

                            <!-- Filter Rating -->
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Rating</label>
                                <select name="rating"
                                    class="w-full px-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all bg-white">
                                    <option value="">Semua Rating</option>
                                    @for ($r = 5; $r >= 1; $r--)
                                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>
                                            {{ $r }} Bintang
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 justify-end mt-4">
                        @if (request()->hasAny(['q', 'status', 'rating']))
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

            <!-- Active Filter Tags - DIPERBAIKI -->
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

            @if ($testimonis->count() > 0)
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50/50">
                                <th class="px-4 py-4 text-center w-10"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500">PENGIRIM & TESTIMONI</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 w-24">AKSI</th>
                             </tr>
                        </thead>
                        <tbody>
                            @foreach ($testimonis as $t)
                                @php
                                    $isPending = !$t->is_approved && !isset($t->rejected_at);
                                    $isApproved = $t->is_approved;
                                    $statusBadge = $isApproved
                                        ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>Tampil</span>'
                                        : '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1"></span>Menunggu</span>';
                                @endphp
                                
                                <tr class="border-b border-gray-100 hover:bg-green-50/20 cursor-pointer expandable-row {{ $isPending ? 'bg-yellow-50/20' : '' }}"
                                    data-target="detail-{{ $t->id }}">
                                    <td class="px-4 py-4 text-center">
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon inline-block" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                     </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                                <span class="text-sm font-semibold text-green-700">
                                                    {{ strtoupper(substr($t->nama_pengirim, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="text-sm font-semibold text-gray-900">{{ $t->nama_pengirim }}</span>
                                                    @if ($t->pekerjaan)
                                                        <span class="text-xs text-gray-400">• {{ $t->pekerjaan }}</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-0.5 mt-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3.5 h-3.5 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                        </svg>
                                                    @endfor
                                                    <span class="ml-1 text-xs text-gray-500">{{ $t->rating }}/5</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1 line-clamp-2">{{ Str::limit($t->isi_testimoni, 100) }}</div>
                                                <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                    {!! $statusBadge !!}
                                                    <span class="text-xs text-gray-400">{{ $t->created_at->format('d M Y') }}</span>
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1">Klik untuk lihat detail</div>
                                            </div>
                                        </div>
                                     </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative inline-block">
                                            <button type="button"
                                                class="dropdown-toggle inline-flex items-center justify-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                data-id="{{ $t->id }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                </svg>
                                            </button>
                                        </div>
                                     </td>
                                 </tr>

                                <!-- Expandable Row -->
                                <tr id="detail-{{ $t->id }}" class="hidden border-b border-gray-100">
                                    <td class="px-4 py-4 bg-gray-50/30"></td>
                                    <td colspan="2" class="px-6 py-4 bg-gray-50/30">
                                        <div class="space-y-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                                <h3 class="text-sm font-semibold text-gray-800">Detail Testimoni</h3>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                                <!-- Kolom 1: Data Pengirim -->
                                                <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Data Pengirim</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400">Nama Lengkap</p>
                                                            <p class="text-sm font-medium text-gray-900">{{ $t->nama_pengirim }}</p>
                                                        </div>
                                                        @if ($t->pekerjaan)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Pekerjaan</p>
                                                                <p class="text-sm text-gray-700">{{ $t->pekerjaan }}</p>
                                                            </div>
                                                        @endif
                                                        @if ($t->muzakki)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Email Terkait</p>
                                                                <p class="text-sm text-gray-700">{{ $t->muzakki->pengguna->email ?? '-' }}</p>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-xs text-gray-400">Tanggal Kirim</p>
                                                            <p class="text-sm text-gray-700">{{ $t->created_at->format('d F Y, H:i') }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Kolom 2: Isi Testimoni -->
                                                <div class="bg-white rounded-xl border border-gray-200 p-4 md:col-span-1">
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Isi Testimoni</h4>
                                                    <div class="flex items-center gap-0.5 mb-3">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endfor
                                                        <span class="ml-2 text-sm font-semibold text-gray-700">{{ $t->rating }}/5</span>
                                                    </div>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                                    </div>
                                                </div>

                                                <!-- Kolom 3: Status & Aksi -->
                                                <div class="bg-white rounded-xl border border-gray-200 p-4">
                                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Status & Aksi</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-400 mb-1">Status Saat Ini</p>
                                                            {!! $statusBadge !!}
                                                        </div>
                                                        @if ($t->is_approved && $t->approvedBy)
                                                            <div>
                                                                <p class="text-xs text-gray-400">Disetujui Oleh</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $t->approvedBy->username }}</p>
                                                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($t->approved_at)->format('d/m/Y H:i') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Tombol Aksi -->
                                                    <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                                                        @if (!$t->is_approved)
                                                            <form action="{{ route('superadmin.testimoni.approve', $t->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-semibold rounded-lg transition-all">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                    Tampilkan di Landing
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('superadmin.testimoni.reject', $t->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-xs font-semibold rounded-lg transition-all">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                                    </svg>
                                                                    Sembunyikan
                                                                </button>
                                                            </form>
                                                        @endif
                                                        
                                                        <form action="{{ route('superadmin.testimoni.destroy', $t->id) }}" method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini secara permanen?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition-all">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Hapus Permanen
                                                            </button>
                                                        </form>
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

                <!-- MOBILE VIEW -->
                <div class="block md:hidden divide-y divide-gray-100">
                    @foreach ($testimonis as $t)
                        @php
                            $isPending = !$t->is_approved && !isset($t->rejected_at);
                            $statusBadge = $t->is_approved
                                ? '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Tampil</span>'
                                : '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>';
                        @endphp
                        
                        <div class="p-4 {{ $isPending ? 'bg-yellow-50/20' : '' }}">
                            <div class="expandable-row-mobile cursor-pointer" data-target="detail-mobile-{{ $t->id }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile" 
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                            <span class="text-xs text-gray-400">Testimoni</span>
                                        </div>
                                        <div class="flex items-center justify-between gap-2 flex-wrap">
                                            <h3 class="text-sm font-semibold text-gray-800">{{ $t->nama_pengirim }}</h3>
                                            {!! $statusBadge !!}
                                        </div>
                                        <div class="flex items-center gap-0.5 mt-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-1 text-xs text-gray-500">{{ $t->rating }}/5</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($t->isi_testimoni, 80) }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $t->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 flex-shrink-0">
                                        <button type="button"
                                            class="dropdown-toggle-mobile p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                                            data-id="{{ $t->id }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                            </svg>
                                        </button>
                                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200 expand-icon-mobile-chevron" 
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div id="detail-mobile-{{ $t->id }}" class="hidden mt-3 pt-3 border-t border-gray-100">
                                <div class="space-y-3">
                                    @if ($t->pekerjaan)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500">Pekerjaan</h4>
                                            <p class="text-sm text-gray-600">{{ $t->pekerjaan }}</p>
                                        </div>
                                    @endif
                                    @if ($t->muzakki)
                                        <div>
                                            <h4 class="text-xs font-semibold text-gray-500">Email</h4>
                                            <p class="text-sm text-gray-600">{{ $t->muzakki->pengguna->email ?? '-' }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="text-xs font-semibold text-gray-500">Isi Testimoni</h4>
                                        <div class="bg-gray-50 rounded-lg p-3 mt-1">
                                            <p class="text-sm text-gray-700 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-2 border-t border-gray-200 space-y-2">
                                        @if (!$t->is_approved)
                                            <form action="{{ route('superadmin.testimoni.approve', $t->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-semibold rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    Tampilkan di Landing
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('superadmin.testimoni.reject', $t->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-xs font-semibold rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                                    Sembunyikan
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('superadmin.testimoni.destroy', $t->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus Permanen
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($testimonis->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $testimonis->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    @if(request('q') || request('status') || request('rating'))
                        <p class="text-sm text-gray-500 mb-2">Tidak ada hasil untuk filter yang dipilih</p>
                        <a href="{{ route('superadmin.testimoni.index') }}" class="text-sm text-green-600 hover:text-green-700">Reset semua filter</a>
                    @else
                        <p class="text-sm text-gray-500 mb-2">Belum ada testimoni</p>
                        <p class="text-xs text-gray-400">Testimoni akan muncul setelah muzakki mengirimkannya</p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Container -->
    <div id="dropdown-container" class="fixed hidden z-[9999] bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden" style="min-width: 160px;">
        <div class="py-1" id="dd-actions"></div>
    </div>

    <style>
        .rotate-90 { transform: rotate(90deg); }
        .rotate-180 { transform: rotate(180deg); }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
@endsection

@push('scripts')
<script>
// Dropdown handling
let currentDropdown = null;

function closeDropdown() {
    const dropdown = document.getElementById('dropdown-container');
    if (dropdown) {
        dropdown.classList.add('hidden');
        dropdown.removeAttribute('data-id');
    }
    currentDropdown = null;
}

function positionDropdown(toggle) {
    const dropdown = document.getElementById('dropdown-container');
    const rect = toggle.getBoundingClientRect();
    const margin = 6;
    
    dropdown.style.visibility = 'hidden';
    
    requestAnimationFrame(() => {
        const ddW = dropdown.offsetWidth;
        const ddH = dropdown.offsetHeight;
        const vpW = window.innerWidth;
        const vpH = window.innerHeight;
        
        let left = rect.right - ddW;
        if (left < margin) left = margin;
        if (left + ddW > vpW - margin) left = vpW - ddW - margin;
        
        let top = rect.bottom + margin;
        if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
        if (top < margin) top = margin;
        
        dropdown.style.top = top + 'px';
        dropdown.style.left = left + 'px';
        dropdown.style.visibility = '';
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.getElementById('filterButton');
    const filterPanel = document.getElementById('filterPanel');
    // HAPUS baris ini - elemen 'closeFilterPanelBtn' tidak ada di HTML
    // const closeBtn = document.getElementById('closeFilterPanelBtn');
    
    if (filterButton && filterPanel) {
        filterButton.addEventListener('click', () => filterPanel.classList.toggle('hidden'));
    }
    
    // HAPUS block ini karena closeBtn tidak ada
    // if (closeBtn && filterPanel) {
    //     closeBtn.addEventListener('click', () => filterPanel.classList.add('hidden'));
    // }

    // Desktop expandable
    document.querySelectorAll('.expandable-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown-toggle, button, a, form')) return;
            const targetId = this.getAttribute('data-target');
            const targetRow = document.getElementById(targetId);
            const icon = this.querySelector('.expand-icon');
            if (targetRow) {
                const isHidden = targetRow.classList.contains('hidden');
                targetRow.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-90');
            }
        });
    });

    // Mobile expandable
    document.querySelectorAll('.expandable-row-mobile').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown-toggle-mobile, button, a, form')) return;
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const icon = this.querySelector('.expand-icon-mobile');
            const chevron = this.querySelector('.expand-icon-mobile-chevron');
            if (targetContent) {
                const isHidden = targetContent.classList.contains('hidden');
                targetContent.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-90');
                if (chevron) chevron.classList.toggle('rotate-90');
            }
        });
    });

    // Desktop dropdown
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const dropdown = document.getElementById('dropdown-container');
            const ddActions = document.getElementById('dd-actions');
            
            if (!dropdown || !ddActions) return;
            
            if (dropdown.dataset.id === id && !dropdown.classList.contains('hidden')) {
                closeDropdown();
                return;
            }
            
            closeDropdown();
            dropdown.dataset.id = id;
            
            const row = document.querySelector(`[data-target="detail-${id}"]`);
            const isApproved = row && row.querySelector('.bg-green-100') !== null;
            
            const approveUrl = `{{ url('superadmin-testimoni') }}/${id}/approve`;
            const rejectUrl = `{{ url('superadmin-testimoni') }}/${id}/reject`;
            const destroyUrl = `{{ url('superadmin-testimoni') }}/${id}`;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            ddActions.innerHTML = !isApproved ? `
                <form action="${approveUrl}" method="POST" class="block">
                    <input type="hidden" name="_token" value="${csrf}">
                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tampilkan
                    </button>
                </form>
            ` : `
                <form action="${rejectUrl}" method="POST" class="block">
                    <input type="hidden" name="_token" value="${csrf}">
                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Sembunyikan
                    </button>
                </form>
            ` + `
                <form action="${destroyUrl}" method="POST" class="block" onsubmit="return confirm('Hapus testimoni ini secara permanen?')">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            `;
            
            dropdown.classList.remove('hidden');
            positionDropdown(toggle);
        });
    });
    
    // Mobile dropdown
    document.querySelectorAll('.dropdown-toggle-mobile').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.dataset.id;
            const dropdown = document.getElementById('dropdown-container');
            const ddActions = document.getElementById('dd-actions');
            
            if (!dropdown || !ddActions) return;
            
            if (dropdown.dataset.id === id && !dropdown.classList.contains('hidden')) {
                closeDropdown();
                return;
            }
            
            closeDropdown();
            dropdown.dataset.id = id;
            
            const row = document.querySelector(`[data-target="detail-mobile-${id}"]`);
            const isApproved = row && row.querySelector('.bg-green-100') !== null;
            
            const approveUrl = `{{ url('superadmin-testimoni') }}/${id}/approve`;
            const rejectUrl = `{{ url('superadmin-testimoni') }}/${id}/reject`;
            const destroyUrl = `{{ url('superadmin-testimoni') }}/${id}`;
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            ddActions.innerHTML = !isApproved ? `
                <form action="${approveUrl}" method="POST" class="block">
                    <input type="hidden" name="_token" value="${csrf}">
                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Tampilkan
                    </button>
                </form>
            ` : `
                <form action="${rejectUrl}" method="POST" class="block">
                    <input type="hidden" name="_token" value="${csrf}">
                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Sembunyikan
                    </button>
                </form>
            ` + `
                <form action="${destroyUrl}" method="POST" class="block" onsubmit="return confirm('Hapus testimoni ini secara permanen?')">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus
                    </button>
                </form>
            `;
            
            dropdown.classList.remove('hidden');
            positionDropdown(toggle);
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('dropdown-container');
        if (dropdown && !dropdown.contains(e.target) && !e.target.closest('.dropdown-toggle') && !e.target.closest('.dropdown-toggle-mobile')) {
            closeDropdown();
        }
    });
    
    // Close dropdown on scroll/resize
    window.addEventListener('scroll', closeDropdown, true);
    window.addEventListener('resize', closeDropdown);
});

function removeFilter(filterName) {
    const url = new URL(window.location.href);
    url.searchParams.delete(filterName);
    url.searchParams.set('page', '1');
    window.location.href = url.toString();
}

// Tambahkan fungsi toggleFilter untuk tombol "Tutup" di filter panel
function toggleFilter() {
    const filterPanel = document.getElementById('filterPanel');
    if (filterPanel) {
        filterPanel.classList.add('hidden');
    }
}
</script>
@endpush