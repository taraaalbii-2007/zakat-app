{{-- resources/views/admin-lembaga/amil/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Data Amil')

@section('content')
    <div class="space-y-4 sm:space-y-6">

        {{-- ── Import Error Alert ──────────────────────────────────────── --}}
        @if (session('import_errors') && count(session('import_errors')) > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl px-5 py-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-500 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-yellow-800">
                            {{ count(session('import_errors')) }} baris dilewati saat import
                        </p>
                        <button type="button" id="btn-toggle-import-errors"
                            class="text-xs text-yellow-600 hover:text-yellow-800 mt-1">
                            Lihat detail error ▾
                        </button>
                        <ul id="import-errors-list" class="hidden mt-2 space-y-0.5 text-xs text-yellow-700">
                            @foreach (session('import_errors') as $err)
                                <li>• {{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Main Card ────────────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- ── Card Header ────────────────────────────────────────── --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Amil</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $amils->total() }} Amil</p>
                    </div>

                    {{-- ── Action Buttons ──────────────────────────────── --}}
                    <div class="flex flex-wrap gap-2">

                        {{-- Tambah --}}
                        <a href="{{ route('amil.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2
                               bg-primary hover:bg-primary-600 text-white text-sm font-medium
                               rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Tambah
                            </span>
                        </a>

                        {{-- Download Template --}}
                        <a href="{{ route('import.template') }}"
                            class="group inline-flex items-center justify-center px-3 py-2
                               bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium
                               rounded-lg transition-all"
                            title="Download Template Excel Import">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                           a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Template
                            </span>
                        </a>

                        {{-- Import --}}
                        <button type="button" onclick="document.getElementById('modal-import').classList.remove('hidden')"
                            class="group inline-flex items-center justify-center px-3 py-2
                               bg-green-600 hover:bg-green-700 text-white text-sm font-medium
                               rounded-lg transition-all shadow-sm"
                            title="Import Data Amil dari Excel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 11l3 3m0 0l3-3m-3 3V4" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Import
                            </span>
                        </button>

                        {{-- Export Excel --}}
                        <a href="{{ route('export.excel', request()->query()) }}"
                            class="group inline-flex items-center justify-center px-3 py-2
                               bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium
                               rounded-lg transition-all shadow-sm"
                            title="Export Data Amil ke Excel">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                           a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Export
                            </span>
                        </a>

                        {{-- Filter --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2
                               bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium
                               rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414
                                           a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Filter
                            </span>
                        </button>

                        {{-- Search Form --}}
                        <div id="search-container" class="transition-all duration-300"
                            style="{{ request('q') ? 'min-width: 280px;' : '' }}">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('amil.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if (request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if (request('jenis_kelamin'))
                                    <input type="hidden" name="jenis_kelamin" value="{{ request('jenis_kelamin') }}">
                                @endif
                                @if (request('lembaga_id'))
                                    <input type="hidden" name="lembaga_id" value="{{ request('lembaga_id') }}">
                                @endif
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                        placeholder="Cari nama, kode, email, telepon..."
                                        class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg
                                               bg-white placeholder-gray-500 focus:outline-none focus:ring-2
                                               focus:ring-primary focus:border-primary transition-all">
                                </div>
                            </form>
                        </div>

                    </div>{{-- /action buttons --}}
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request('status') || request('jenis_kelamin') || request('lembaga_id') ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('amil.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" onchange="this.form.submit()"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>
                        @if (auth()->user()->peran === 'superadmin')
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Lembaga</label>
                                <select name="lembaga_id" onchange="this.form.submit()"
                                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    <option value="">Semua Lembaga</option>
                                    @foreach ($lembagas as $lembaga)
                                        <option value="{{ $lembaga->id }}"
                                            {{ request('lembaga_id') == $lembaga->id ? 'selected' : '' }}>
                                            {{ $lembaga->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                    @if (request('status') || request('jenis_kelamin') || request('lembaga_id'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('amil.index', request('q') ? ['q' => request('q')] : []) }}"
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

            {{-- ── Active Filter Badge ─────────────────────────────────── --}}
            @if ($amils->count() > 0 && (request('q') || request('status') || request('jenis_kelamin')))
                <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                    <div class="flex items-center flex-wrap gap-2">
                        <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                        @if (request('q'))
                            <span
                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Pencarian: "{{ request('q') }}"
                                <button type="button" onclick="removeFilter('q')"
                                    class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                            </span>
                        @endif
                        @if (request('status'))
                            <span
                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                Status: {{ ucfirst(request('status')) }}
                                <button type="button" onclick="removeFilter('status')"
                                    class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                            </span>
                        @endif
                        @if (request('jenis_kelamin'))
                            <span
                                class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                {{ request('jenis_kelamin') === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                <button type="button" onclick="removeFilter('jenis_kelamin')"
                                    class="ml-1.5 text-blue-600 hover:text-blue-800">×</button>
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ── Tabel / Card / Empty ────────────────────────────────── --}}
            @if ($amils->count() > 0)

                {{-- Tabel Container --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="w-12 px-4 py-3"></th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amil</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kontak</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="tbody-lembaga">
                            @include('admin-lembaga.amil.partials.table', ['amils' => $amils])
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($amils->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200" id="pagination-container">
                        {{ $amils->links() }}
                    </div>
                @endif

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($amils as $amil)
                        @php
                            $colors = [
                                'bg-blue-500',
                                'bg-green-500',
                                'bg-yellow-500',
                                'bg-red-500',
                                'bg-purple-500',
                                'bg-pink-500',
                                'bg-indigo-500',
                                'bg-orange-500',
                                'bg-teal-500',
                                'bg-cyan-500',
                                'bg-emerald-500',
                                'bg-rose-500',
                            ];
                            $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                            $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
                        @endphp
                        <div class="expandable-card">
                            <div class="p-4 hover:bg-gray-50 transition-colors cursor-pointer expandable-row-mobile"
                                data-target="detail-mobile-{{ $amil->uuid }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center flex-1 min-w-0">
                                        <div class="flex-shrink-0 mr-3">
                                            @if ($amil->foto)
                                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100"
                                                    src="{{ Storage::url($amil->foto) }}"
                                                    alt="{{ $amil->nama_lengkap }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                    <span
                                                        class="text-sm font-medium text-white">{{ $initial }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                                    {{ $amil->nama_lengkap }}</h3>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0
                                                    {{ $amil->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                    {{ $amil->jenis_kelamin === 'L' ? 'L' : 'P' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center mt-1 gap-2">
                                                @if ($amil->status === 'aktif')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                                @elseif ($amil->status === 'cuti')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Cuti</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Nonaktif</span>
                                                @endif
                                                <span class="text-xs text-gray-500">{{ $amil->kode_amil }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 ml-2">
                                        <button type="button"
                                            class="dropdown-toggle p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                                            data-uuid="{{ $amil->uuid }}" data-nama="{{ $amil->nama_lengkap }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon-mobile"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile Expandable --}}
                            <div id="detail-mobile-{{ $amil->uuid }}" class="hidden expandable-content-mobile">
                                <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 space-y-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Telepon</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $amil->telepon }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Email</p>
                                        <p class="text-sm font-medium text-gray-900 break-all">{{ $amil->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Alamat</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $amil->alamat }}</p>
                                    </div>
                                    <div class="pt-3 border-t border-gray-200 flex gap-2">
                                        <a href="{{ route('amil.show', $amil->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2
                                               bg-primary hover:bg-primary-600 text-white text-xs font-medium rounded-lg transition-all">
                                            Detail
                                        </a>
                                        <a href="{{ route('amil.edit', $amil->uuid) }}"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2
                                               bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-lg transition-all">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="p-8 sm:p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    @if (request('q') || request('status') || request('jenis_kelamin'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">Tidak ada amil yang sesuai dengan filter yang dipilih.</p>
                        <a href="{{ route('amil.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200
                               text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Filter
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Amil</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan data amil untuk mengelola pengurus zakat.</p>
                        <div class="flex items-center justify-center gap-3 flex-wrap">
                            <a href="{{ route('amil.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600
                                   text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                Tambah Amil
                            </a>
                            <button type="button"
                                onclick="document.getElementById('modal-import').classList.remove('hidden')"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700
                                   text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                Import dari Excel
                            </button>
                        </div>
                    @endif
                </div>
            @endif

        </div>{{-- /main card --}}
    </div>{{-- /space-y --}}


    {{-- ── Dropdown Container ──────────────────────────────────────────── --}}
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-52 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                <a href="#" id="dropdown-detail-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <button type="button" id="dropdown-status-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    Ubah Status
                </button>
                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- ── Modal Ubah Status ───────────────────────────────────────────── --}}
    <div id="status-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-yellow-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Ubah Status Amil</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Pilih status baru untuk "<span id="modal-status-name" class="font-semibold text-gray-700"></span>"
            </p>
            <p class="text-xs text-gray-400 text-center mb-5">Perubahan akan langsung diterapkan.</p>
            <form id="status-form" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-3 mb-5 sm:mb-6">
                    <label
                        class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="status" value="aktif"
                            class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm font-medium text-gray-900">Aktif</span>
                    </label>
                    <label
                        class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="status" value="cuti"
                            class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm font-medium text-gray-900">Cuti</span>
                    </label>
                    <label
                        class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="radio" name="status" value="nonaktif"
                            class="w-4 h-4 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm font-medium text-gray-900">Nonaktif</span>
                    </label>
                </div>
                <div class="flex justify-center gap-2 sm:gap-3">
                    <button type="button" id="cancel-status-btn"
                        class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-yellow-600 text-xs sm:text-sm font-medium text-white hover:bg-yellow-700 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Modal Hapus ─────────────────────────────────────────────────── --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 text-center">Hapus Amil</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus amil
                "<span id="modal-amil-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 text-center">
                Tindakan ini tidak dapat dibatalkan dan akan menghapus akun login terkait.
            </p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5
                       bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5
                           bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Modal Import Data dari Excel ───────────────────────────────── --}}
    <div id="modal-import"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 w-full max-w-md">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">Import Data Amil</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Upload file Excel untuk import data amil</p>
                </div>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data"
                id="form-upload-import">
                @csrf
                <div class="px-6 py-5 space-y-4">

                    {{-- Panduan --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 text-xs text-blue-700 space-y-1">
                        <p class="font-semibold text-blue-800">Panduan Import:</p>
                        <p>• Download template terlebih dahulu, isi data, lalu upload.</p>
                        <p>• Format file: .xlsx atau .xls (maks. 500 MB).</p>
                        <p>• Akun login amil dibuat otomatis, notifikasi dikirim via email.</p>
                    </div>

                    {{-- Drop Zone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            File Excel <span class="text-red-500">*</span>
                        </label>
                        <div id="import-drop-zone"
                            class="relative flex flex-col items-center justify-center w-full h-36
                               border-2 border-dashed border-gray-300 rounded-xl
                               hover:border-green-400 hover:bg-green-50/50
                               transition-all cursor-pointer bg-gray-50"
                            onclick="document.getElementById('file-input-import').click()">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                           a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm text-gray-500 text-center px-4">Klik atau seret file Excel ke sini</p>
                            <p class="text-xs text-gray-400 mt-1">.xlsx / .xls — maks. 500 MB</p>
                            <input type="file" name="file_import" id="file-input-import" accept=".xlsx,.xls"
                                class="hidden" required onchange="onImportFileSelected(this)">
                        </div>

                        <div id="import-file-preview"
                            class="hidden mt-2 flex items-center gap-2 text-sm text-green-700 font-medium
                               bg-green-50 border border-green-100 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-green-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span id="import-file-name" class="truncate"></span>
                            <button type="button" onclick="clearImportFile()"
                                class="ml-auto text-gray-400 hover:text-red-500 transition-colors shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    @error('file_import')
                        <p class="text-xs text-red-600">{{ $message }}</p>
                    @enderror

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300
           rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-upload-submit"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white
           bg-green-600 hover:bg-green-700 rounded-xl shadow-sm transition-all
           disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        Lanjut
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let currentUuid = null;
        let currentNama = null;

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const detailLink = document.getElementById('dropdown-detail-link');
            const editLink = document.getElementById('dropdown-edit-link');
            const statusBtn = document.getElementById('dropdown-status-btn');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const tableContainer = document.getElementById('table-container');

            // ── Dropdown toggle ──────────────────────────────────────────────
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();

                    const uuid = toggle.getAttribute('data-uuid');
                    const nama = toggle.getAttribute('data-nama');

                    // Tutup jika klik tombol yang sama
                    if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    currentUuid = uuid;
                    currentNama = nama;
                    dropdownContainer.setAttribute('data-current-uuid', uuid);

                    // Posisi dropdown
                    const rect = toggle.getBoundingClientRect();
                    dropdownContainer.style.visibility = 'hidden';
                    dropdownContainer.classList.remove('hidden');

                    const dropdownW = dropdownContainer.offsetWidth;
                    const dropdownH = dropdownContainer.offsetHeight;

                    let top = rect.bottom + 4;
                    let left = rect.right - dropdownW;

                    if (left < 10) left = 10;
                    if (left + dropdownW > window.innerWidth - 10) left = window.innerWidth - dropdownW -
                    10;
                    if (rect.bottom + dropdownH > window.innerHeight) top = rect.top - dropdownH - 4;
                    if (top < 4) top = 4;

                    dropdownContainer.style.top = top + 'px';
                    dropdownContainer.style.left = left + 'px';
                    dropdownContainer.style.visibility = '';

                    // Set link
                    detailLink.href = `/amil/${uuid}`;
                    editLink.href = `/amil/${uuid}/edit`;

                } else if (!dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });

            // Tutup dropdown saat scroll / resize
            const closeDropdown = () => {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            };
            window.addEventListener('scroll', closeDropdown, true);
            window.addEventListener('resize', closeDropdown);
            if (tableContainer) tableContainer.addEventListener('scroll', closeDropdown, true);

            // ── Expandable Row Desktop ───────────────────────────────────────
            document.querySelectorAll('.expandable-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    // Jangan expand jika klik dropdown toggle atau link di dalam row
                    if (e.target.closest('.dropdown-toggle') || e.target.closest('a')) return;

                    const targetId = row.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const icon = row.querySelector('.expand-icon');

                    if (!content) return;

                    const isHidden = content.classList.contains('hidden');

                    // Tutup semua yang terbuka
                    document.querySelectorAll('.expandable-content').forEach(function(el) {
                        el.classList.add('hidden');
                    });
                    document.querySelectorAll('.expand-icon').forEach(function(ic) {
                        ic.classList.remove('rotate-90');
                    });

                    // Toggle yang diklik
                    if (isHidden) {
                        content.classList.remove('hidden');
                        if (icon) icon.classList.add('rotate-90');
                    }
                });
            });

            // ── Expandable Row Mobile ────────────────────────────────────────
            document.querySelectorAll('.expandable-row-mobile').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('.dropdown-toggle')) return;

                    const targetId = row.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const card = row.closest('.expandable-card');
                    const icon = card ? card.querySelector('.expand-icon-mobile') : null;

                    if (!content) return;

                    content.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                });
            });

            // ── Ubah Status ──────────────────────────────────────────────────
            statusBtn.addEventListener('click', function() {
                if (!currentUuid) return;
                dropdownContainer.classList.add('hidden');
                document.getElementById('modal-status-name').textContent = currentNama;
                document.getElementById('status-form').action = `/amil/${currentUuid}/toggle-status`;
                document.getElementById('status-modal').classList.remove('hidden');
            });

            document.getElementById('cancel-status-btn').addEventListener('click', function() {
                document.getElementById('status-modal').classList.add('hidden');
            });
            document.getElementById('status-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // ── Hapus ────────────────────────────────────────────────────────
            deleteBtn.addEventListener('click', function() {
                if (!currentUuid) return;
                dropdownContainer.classList.add('hidden');
                document.getElementById('modal-amil-name').textContent = currentNama;
                document.getElementById('delete-form').action = `/amil/${currentUuid}`;
                document.getElementById('delete-modal').classList.remove('hidden');
            });

            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // ── Modal Import ─────────────────────────────────────────────────
            const modalImport = document.getElementById('modal-import');
            if (modalImport) {
                modalImport.addEventListener('click', function(e) {
                    if (e.target === this) closeImportModal();
                });
            }

            // ── Import Error Toggle ──────────────────────────────────────────
            const btnToggleErrors = document.getElementById('btn-toggle-import-errors');
            if (btnToggleErrors) {
                btnToggleErrors.addEventListener('click', function() {
                    const list = document.getElementById('import-errors-list');
                    list.classList.toggle('hidden');
                    this.textContent = list.classList.contains('hidden') ? 'Lihat detail error ▾' :
                        'Sembunyikan ▴';
                });
            }
        });

        // ── Filter & Search ──────────────────────────────────────────────────
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
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        function removeFilter(key) {
            const url = new URL(window.location.href);
            url.searchParams.delete(key);
            window.location.href = url.toString();
        }

        // ── Import Modal ─────────────────────────────────────────────────────
        function openImportModal() {
            document.getElementById('modal-import').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('modal-import').classList.add('hidden');
            clearImportFile();
        }

        function onImportFileSelected(input) {
            if (input.files && input.files[0]) {
                document.getElementById('import-file-name').textContent = input.files[0].name;
                document.getElementById('import-file-preview').classList.remove('hidden');
                document.getElementById('btn-upload-submit').disabled = false;
            }
        }

        function clearImportFile() {
            document.getElementById('file-input-import').value = '';
            document.getElementById('import-file-preview').classList.add('hidden');
            document.getElementById('btn-upload-submit').disabled = true;
        }
    </script>
@endpush
