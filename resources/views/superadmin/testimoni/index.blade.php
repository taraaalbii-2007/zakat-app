{{-- resources/views/superadmin/testimoni/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola Testimoni')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ── Stats Cards ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Total Testimoni</span>
            <span class="text-2xl font-bold text-gray-900">{{ number_format($stats['total'] ?? 0) }}</span>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Menunggu Review</span>
            <span class="text-2xl font-bold text-amber-600">{{ number_format($stats['pending'] ?? 0) }}</span>
            @if (($stats['pending'] ?? 0) > 0)
                <span class="text-xs text-amber-600 font-medium mt-0.5">Perlu ditinjau</span>
            @endif
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-4 py-3 flex flex-col gap-1">
            <span class="text-xs text-gray-500 font-medium">Tampil di Landing</span>
            <span class="text-2xl font-bold text-green-600">{{ number_format($stats['approved'] ?? 0) }}</span>
        </div>
    </div>

    {{-- ── Main Card ── --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Testimoni</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $testimonis->total() }} Testimoni</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">

                    {{-- Filter --}}
                    <button type="button" onclick="toggleFilter()"
                        class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto
                        {{ request()->hasAny(['status', 'rating', 'q']) ? 'ring-2 ring-primary' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        @if (request()->hasAny(['status', 'rating']))
                            <span class="ml-1.5 inline-flex items-center justify-center w-4 h-4 text-xs font-bold bg-primary text-white rounded-full">
                                {{ collect(['status','rating'])->filter(fn($k) => request($k))->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Search --}}
                    <div id="search-container" class="transition-all duration-300" style="{{ request('q') ? 'min-width:280px;' : '' }}">
                        <button type="button" onclick="toggleSearch()" id="search-button"
                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2">Cari</span>
                        </button>
                        <form method="GET" action="{{ route('superadmin.testimoni.index') }}" id="search-form"
                            class="{{ request('q') ? '' : 'hidden' }}">
                            @foreach (['status', 'rating'] as $filter)
                                @if (request($filter))
                                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                                @endif
                            @endforeach
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                        placeholder="Cari nama, testimoni..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                </div>
                                @if (request()->hasAny(['q','status','rating']))
                                    <a href="{{ route('superadmin.testimoni.index') }}"
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
            class="{{ request()->hasAny(['status','rating']) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('superadmin.testimoni.index') }}" id="filter-form">
                @if (request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Menunggu Review</option>
                            <option value="approved"  {{ request('status') == 'approved'  ? 'selected' : '' }}>Sudah Ditampilkan</option>
                            <option value="rejected"  {{ request('status') == 'rejected'  ? 'selected' : '' }}>Disembunyikan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Rating</label>
                        <select name="rating"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                            onchange="this.form.submit()">
                            <option value="">Semua Rating</option>
                            @for ($r = 5; $r >= 1; $r--)
                                <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>
                                    {{ $r }} Bintang
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                @if (request()->hasAny(['status','rating']))
                    <div class="mt-3 flex justify-end">
                        <a href="{{ route('superadmin.testimoni.index', request('q') ? ['q' => request('q')] : []) }}"
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

        @if ($testimonis->count() > 0)

            {{-- ── Desktop View ── --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-12 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim & Testimoni</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($testimonis as $t)
                            @php
                                $isPending  = !$t->is_approved && !isset($t->rejected_at);
                                $isApproved = $t->is_approved;

                                $statusBadge = $isApproved
                                    ? '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Tampil</span>'
                                    : '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Menunggu</span>';
                            @endphp

                            <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row {{ $isPending ? 'bg-amber-50/20' : '' }}"
                                data-target="detail-{{ $t->id }}">
                                <td class="px-4 py-4">
                                    <button type="button" class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-primary-100 border border-primary-200 flex items-center justify-center flex-shrink-0">
                                                <span class="text-xs font-bold text-primary-700">
                                                    {{ strtoupper(substr($t->nama_pengirim, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $t->nama_pengirim }}</div>
                                                @if ($t->pekerjaan)
                                                    <div class="text-xs text-gray-500">{{ $t->pekerjaan }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-0.5 mt-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <svg class="w-3.5 h-3.5 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-1 text-xs text-gray-500">{{ $t->rating }}/5</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $t->isi_testimoni }}</div>
                                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                            {!! $statusBadge !!}
                                            <span class="text-xs text-gray-400">{{ $t->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">Klik baris untuk melihat detail</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button type="button"
                                        class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                        data-id="{{ $t->id }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>

                            {{-- Expandable Content --}}
                            <tr id="detail-{{ $t->id }}" class="hidden expandable-content">
                                <td colspan="3" class="px-0 py-0">
                                    <div class="bg-gray-50 border-y border-gray-100">
                                        <div class="px-6 py-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                                {{-- Kolom 1: Data Pengirim --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Data Pengirim</h4>
                                                    <div class="space-y-3">
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Nama</p>
                                                                <p class="text-sm font-medium text-gray-900">{{ $t->nama_pengirim }}</p>
                                                            </div>
                                                        </div>
                                                        @if ($t->pekerjaan)
                                                            <div class="flex items-start gap-2">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Pekerjaan</p>
                                                                    <p class="text-sm text-gray-900">{{ $t->pekerjaan }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if ($t->muzakki)
                                                            <div class="flex items-start gap-2">
                                                                <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Akun Muzakki</p>
                                                                    <p class="text-sm text-gray-900">{{ $t->muzakki->pengguna->email ?? '-' }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="flex items-start gap-2">
                                                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                            </svg>
                                                            <div>
                                                                <p class="text-xs text-gray-500">Tanggal Kirim</p>
                                                                <p class="text-sm text-gray-900">{{ $t->created_at->format('d F Y, H:i') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Kolom 2: Isi Testimoni --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Isi Testimoni</h4>
                                                    <div class="flex items-center gap-0.5 mb-3">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <svg class="w-5 h-5 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endfor
                                                        <span class="ml-2 text-sm font-bold text-gray-700">{{ $t->rating }}/5</span>
                                                    </div>
                                                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                                    </div>
                                                </div>

                                                {{-- Kolom 3: Status & Aksi --}}
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Status</h4>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <p class="text-xs text-gray-500 mb-1">Status Saat Ini</p>
                                                            {!! $statusBadge !!}
                                                        </div>
                                                        @if ($t->is_approved && $t->approvedBy)
                                                            <div class="flex items-start gap-2">
                                                                <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                <div>
                                                                    <p class="text-xs text-gray-500">Disetujui Oleh</p>
                                                                    <p class="text-sm text-gray-900">{{ $t->approvedBy->username }}</p>
                                                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($t->approved_at)->format('d/m/Y H:i') }}</p>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Tombol Aksi --}}
                                                    <div class="mt-4 pt-4 border-t border-gray-200 space-y-2">
                                                        @if (!$t->is_approved)
                                                            {{-- APPROVE: route POST, cukup @csrf saja --}}
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
                                                            {{-- REJECT: route POST, cukup @csrf saja --}}
                                                            <form action="{{ route('superadmin.testimoni.reject', $t->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition-all">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                                    </svg>
                                                                    Sembunyikan
                                                                </button>
                                                            </form>
                                                        @endif
                                                        {{-- DESTROY: route DELETE, butuh @method('DELETE') --}}
                                                        <form action="{{ route('superadmin.testimoni.destroy', $t->id) }}" method="POST"
                                                            onsubmit="return confirm('Hapus testimoni ini secara permanen?')">
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
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ── Mobile View ── --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach ($testimonis as $t)
                    @php
                        $isPending  = !$t->is_approved;
                        $isApproved = $t->is_approved;
                        $statusBadge = $isApproved
                            ? '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">Tampil</span>'
                            : '<span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-800">Menunggu</span>';
                    @endphp
                    <div class="expandable-card {{ $isPending ? 'bg-amber-50/20' : '' }}">
                        <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                            data-target="detail-mobile-{{ $t->id }}">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $t->nama_pengirim }}</p>
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
                                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $t->isi_testimoni }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $t->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0 mt-1">
                                    <button type="button"
                                        class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg"
                                        data-id="{{ $t->id }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                        </svg>
                                    </button>
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div id="detail-mobile-{{ $t->id }}" class="hidden expandable-content-mobile">
                            <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-3">
                                @if ($t->pekerjaan)
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Pekerjaan</p>
                                        <p class="text-sm text-gray-900">{{ $t->pekerjaan }}</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Rating</p>
                                    <div class="flex items-center gap-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 fill-current {{ $i <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                        <span class="ml-1 text-sm font-semibold text-gray-700">{{ $t->rating }}/5</span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1.5">Isi Testimoni</p>
                                    <div class="bg-white border border-gray-200 rounded-xl p-3">
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $t->isi_testimoni }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    {!! $statusBadge !!}
                                </div>
                                <div class="pt-2 border-t border-gray-200 space-y-2">
                                    @if (!$t->is_approved)
                                        {{-- APPROVE mobile: route POST, cukup @csrf saja --}}
                                        <form action="{{ route('superadmin.testimoni.approve', $t->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-semibold rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Tampilkan di Landing
                                            </button>
                                        </form>
                                    @else
                                        {{-- REJECT mobile: route POST, cukup @csrf saja --}}
                                        <form action="{{ route('superadmin.testimoni.reject', $t->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 text-xs font-semibold rounded-lg transition-all">
                                                Sembunyikan
                                            </button>
                                        </form>
                                    @endif
                                    {{-- DESTROY mobile: route DELETE, butuh @method('DELETE') --}}
                                    <form action="{{ route('superadmin.testimoni.destroy', $t->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus testimoni ini secara permanen?')">
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

            {{-- Pagination --}}
            @if ($testimonis->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $testimonis->withQueryString()->links() }}
                </div>
            @endif

        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-50 mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                @if (request()->hasAny(['q','status','rating']))
                    <h3 class="text-base font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-500 mb-5">Tidak ada testimoni yang sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('superadmin.testimoni.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        Reset Pencarian
                    </a>
                @else
                    <h3 class="text-base font-medium text-gray-900 mb-2">Belum Ada Testimoni</h3>
                    <p class="text-sm text-gray-500">Belum ada testimoni yang dikirim oleh muzakki.</p>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Dropdown container --}}
<div id="dropdown-container" class="fixed hidden z-[9999]" style="min-width:160px;">
    <div class="w-44 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="py-1" id="dd-actions"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Expand desktop
    document.querySelectorAll('.expandable-row').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, .dropdown-toggle, button, form')) return;
            var target = document.getElementById(this.dataset.target);
            var icon   = this.querySelector('.expand-icon');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-90');
        });
    });

    // ── Expand mobile
    document.querySelectorAll('.expandable-row-mobile').forEach(function (row) {
        row.addEventListener('click', function (e) {
            if (e.target.closest('a, .dropdown-toggle, button, form')) return;
            var target = document.getElementById(this.dataset.target);
            var icon   = this.querySelector('.expand-icon-mobile');
            target.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });

    // ── Dropdown
    var dropdown = document.getElementById('dropdown-container');
    var ddActions = document.getElementById('dd-actions');

    function closeDropdown() {
        dropdown.classList.add('hidden');
        dropdown.removeAttribute('data-id');
    }

    function positionDropdown(toggle) {
    var rect   = toggle.getBoundingClientRect();
    var margin = 6;

    dropdown.style.visibility = 'hidden';

    requestAnimationFrame(function () {
        var ddW  = dropdown.offsetWidth;
        var ddH  = dropdown.offsetHeight;
        var vpW  = window.innerWidth;
        var vpH  = window.innerHeight;

        var left = rect.right - ddW;
        if (left < margin) left = margin;
        if (left + ddW > vpW - margin) left = vpW - ddW - margin;

        var top = rect.bottom + margin;
        if (top + ddH > vpH - margin) top = rect.top - ddH - margin;
        if (top < margin) top = margin;

        dropdown.style.top        = top  + 'px';
        dropdown.style.left       = left + 'px';
        dropdown.style.visibility = '';
    });
}

    document.addEventListener('click', function (e) {
        var toggle = e.target.closest('.dropdown-toggle');
        if (toggle) {
            e.stopPropagation();
            var id = toggle.dataset.id;
            if (dropdown.dataset.id === id && !dropdown.classList.contains('hidden')) {
                closeDropdown(); return;
            }
            dropdown.dataset.id = id;

            var expandRow = document.querySelector('[data-target="detail-' + id + '"]');
            var isApproved = expandRow && expandRow.querySelector('.bg-green-100') !== null;

            var approveUrl = '/superadmin-testimoni/' + id + '/approve';
            var rejectUrl  = '/superadmin-testimoni/' + id + '/reject';
            var destroyUrl = '/superadmin-testimoni/' + id;
            var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

            // APPROVE & REJECT: POST biasa, TIDAK perlu _method
            // DESTROY: perlu _method=DELETE
            ddActions.innerHTML =
                (!isApproved
                    ? '<form action="' + approveUrl + '" method="POST" class="block">' +
                      '<input type="hidden" name="_token" value="' + csrf + '">' +
                      '<button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">' +
                      '<svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Tampilkan</button></form>'
                    : '<form action="' + rejectUrl + '" method="POST" class="block">' +
                      '<input type="hidden" name="_token" value="' + csrf + '">' +
                      '<button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">' +
                      '<svg class="w-4 h-4 mr-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>Sembunyikan</button></form>') +
                '<form action="' + destroyUrl + '" method="POST" class="block" onsubmit="return confirm(\'Hapus permanen?\')">' +
                '<input type="hidden" name="_token" value="' + csrf + '">' +
                '<input type="hidden" name="_method" value="DELETE">' +
                '<button type="submit" class="flex w-full items-center px-4 py-2.5 text-sm text-red-600 hover:bg-gray-50">' +
                '<svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>Hapus</button></form>';

            dropdown.classList.remove('hidden');
            positionDropdown(toggle);
        } else if (!dropdown.contains(e.target)) {
            closeDropdown();
        }
    });

    window.addEventListener('scroll', closeDropdown, true);
    window.addEventListener('resize', closeDropdown);
});

function toggleSearch() {
    var btn = document.getElementById('search-button');
    var form = document.getElementById('search-form');
    var input = document.getElementById('search-input');
    var container = document.getElementById('search-container');
    if (form.classList.contains('hidden')) {
        btn.classList.add('hidden');
        form.classList.remove('hidden');
        container.style.minWidth = '280px';
        setTimeout(function () { input.focus(); }, 50);
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