{{-- resources/views/admin-lembaga/mustahik/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Kelola Data Mustahik')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Mustahik</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $mustahiks->total() }} Mustahik</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.create') }}"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                <span
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Tambah
                                </span>
                            </a>
                        @endif
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span
                                class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
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
                                <span id="search-button-text"
                                    class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                    Cari
                                </span>
                            </button>
                            <form method="GET" action="{{ route('mustahik.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if (request('kategori_id'))
                                    <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                                @endif
                                @if (request('status_verifikasi'))
                                    <input type="hidden" name="status_verifikasi"
                                        value="{{ request('status_verifikasi') }}">
                                @endif
                                @if (request('is_active'))
                                    <input type="hidden" name="is_active" value="{{ request('is_active') }}">
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
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari nama, NIK, no registrasi..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin') ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('mustahik.index') }}" id="filter-form">
                    @if (request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="kategori_id" id="filter-kategori"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Verifikasi</label>
                            <select name="status_verifikasi" id="filter-status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status_verifikasi') == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="verified"
                                    {{ request('status_verifikasi') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="rejected"
                                    {{ request('status_verifikasi') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status Aktif</label>
                            <select name="is_active" id="filter-active"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Nonaktif
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="filter-gender"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                </option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                </option>
                            </select>
                        </div>
                    </div>
                    @if (request('kategori_id') || request('status_verifikasi') || request('is_active') || request('jenis_kelamin'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('mustahik.index', request('q') ? ['q' => request('q')] : []) }}"
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

            @if ($mustahiks->count() > 0)
                {{-- Info Filter Aktif --}}
                @if (request('q'))
                    <div class="px-4 sm:px-6 py-2 bg-blue-50 border-b border-blue-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center flex-wrap gap-2">
                                <span class="text-xs font-medium text-blue-800">Filter Aktif:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    Pencarian: "{{ request('q') }}"
                                    <button type="button" onclick="removeFilter('q')"
                                        class="ml-1.5 text-blue-600 hover:text-blue-800">
                                        ×
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Registrasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mustahik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alamat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($mustahiks as $item)
                                @php
                                    $actions = $item->actions;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->no_registrasi }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $item->tanggal_registrasi->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @php
                                                    // Generate warna berdasarkan huruf pertama
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
                                                    $initial = strtoupper(substr($item->nama_lengkap, 0, 1));
                                                    $colorIndex = $initial ? (ord($initial) - 65) % count($colors) : 0;
                                                    $bgColor = $colors[$colorIndex];
                                                @endphp
                                                <div
                                                    class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                                    <span class="text-sm font-medium text-white">
                                                        {{ $initial }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->nama_lengkap }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    NIK: {{ $item->nik ?? '-' }}
                                                    @if ($item->telepon)
                                                        | {{ $item->telepon }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $item->kategoriMustahik->nama }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ Str::limit($item->alamat, 40) }}</div>
                                        @if ($item->rt_rw)
                                            <div class="text-xs text-gray-500">RT/RW: {{ $item->rt_rw }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            {!! $item->status_badge !!}
                                            {!! $item->active_badge !!}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button" data-dropdown-toggle="{{ $item->uuid }}"
                                                data-nama="{{ $item->nama_lengkap }}"
                                                data-actions="{{ json_encode($actions) }}"
                                                data-status="{{ $item->status_verifikasi }}"
                                                data-is-active="{{ $item->is_active }}"
                                                data-user-role="{{ $permissions['userRole'] }}"
                                                class="dropdown-toggle inline-flex items-center p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @foreach ($mustahiks as $item)
                        @php
                            $actions = $item->actions;
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        @php
                                            // Generate warna berdasarkan huruf pertama
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
                                            $initial = strtoupper(substr($item->nama_lengkap, 0, 1));
                                            $colorIndex = $initial ? (ord($initial) - 65) % count($colors) : 0;
                                            $bgColor = $colors[$colorIndex];
                                        @endphp
                                        <div
                                            class="h-12 w-12 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                                            <span class="text-base font-medium text-white">
                                                {{ $initial }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <!-- ... sisanya tetap sama ... -->
                                    </div>
                                </div>
                                <button type="button" data-dropdown-toggle="{{ $item->uuid }}"
                                    data-nama="{{ $item->nama_lengkap }}" data-actions="{{ json_encode($actions) }}"
                                    data-status="{{ $item->status_verifikasi }}" data-is-active="{{ $item->is_active }}"
                                    data-user-role="{{ $permissions['userRole'] }}"
                                    class="dropdown-toggle flex-shrink-0 ml-2 inline-flex items-center p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($mustahiks->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $mustahiks->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    @if (request('q') || request('kategori_id') || request('status_verifikasi') || request('is_active'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if (request('q'))
                                Tidak ada mustahik yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada mustahik yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('mustahik.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Data Mustahik</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai tambahkan data mustahik penerima zakat.</p>
                        @if ($permissions['canCreate'])
                            <a href="{{ route('mustahik.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Tambah Mustahik
                            </a>
                        @endif
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
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>

                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>

                <button type="button" id="dropdown-verify-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verifikasi
                </button>

                <button type="button" id="dropdown-reject-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak
                </button>

                <button type="button" id="dropdown-toggle-active-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span id="toggle-active-text">Aktifkan</span>
                </button>

                <button type="button" id="dropdown-delete-btn"
                    class="flex items-center w-full px-3 sm:px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors hidden">
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

    {{-- Delete Modal --}}
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Mustahik</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus mustahik
                "<span id="modal-mustahik-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <form id="delete-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirm-delete-btn"
                        class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="reject-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-md shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-3 sm:mb-4">
                <svg class="h-8 w-8 sm:h-10 sm:w-10 text-red-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Tolak Verifikasi
                Mustahik</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Tolak verifikasi mustahik
                "<span id="modal-reject-mustahik-name" class="font-semibold text-gray-700"></span>"?
            </p>

            <div class="mt-4">
                <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="3" placeholder="Masukkan alasan penolakan..."
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    required></textarea>
            </div>

            <div class="flex justify-center gap-2 sm:gap-3 mt-6">
                <button type="button" id="cancel-reject-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-reject-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Tolak
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentDropdownData = null;
        let currentMustahikUuid = null;

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownContainer = document.getElementById('dropdown-container');
            const viewLink = document.getElementById('dropdown-view-link');
            const editLink = document.getElementById('dropdown-edit-link');
            const deleteBtn = document.getElementById('dropdown-delete-btn');
            const verifyBtn = document.getElementById('dropdown-verify-btn');
            const rejectBtn = document.getElementById('dropdown-reject-btn');
            const toggleActiveBtn = document.getElementById('dropdown-toggle-active-btn');
            const tableContainer = document.getElementById('table-container');

            // ── Dropdown open/close ─────────────────────────────
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();

                    const dropdownUuid = toggle.getAttribute('data-dropdown-toggle');
                    const mustahikName = toggle.getAttribute('data-nama');
                    const actions = JSON.parse(toggle.getAttribute('data-actions') || '{}');
                    const status = toggle.getAttribute('data-status');
                    const isActive = toggle.getAttribute('data-is-active') === '1';
                    const userRole = toggle.getAttribute('data-user-role');

                    // Toggle close jika klik tombol yang sama
                    if (dropdownContainer.getAttribute('data-current-uuid') === dropdownUuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    currentMustahikUuid = dropdownUuid;
                    currentDropdownData = {
                        uuid: dropdownUuid,
                        name: mustahikName,
                        actions,
                        status,
                        isActive,
                        userRole
                    };

                    dropdownContainer.setAttribute('data-current-uuid', dropdownUuid);

                    // Hitung posisi dropdown
                    const rect = toggle.getBoundingClientRect();
                    const dropdownWidth = window.innerWidth < 640 ? 176 : 192;
                    const dropdownHeight = 200;

                    let top = rect.bottom;
                    let left = rect.left;

                    if (left + dropdownWidth > window.innerWidth) left = window.innerWidth - dropdownWidth -
                        10;
                    if (top + dropdownHeight > window.innerHeight) top = rect.top - dropdownHeight;

                    dropdownContainer.style.top = top + 'px';
                    dropdownContainer.style.left = left + 'px';

                    // Set link
                    viewLink.href = `/mustahik/${dropdownUuid}`;

                    // Edit
                    if (actions.can_edit) {
                        editLink.href = `/mustahik/${dropdownUuid}/edit`;
                        editLink.classList.remove('hidden');
                    } else {
                        editLink.classList.add('hidden');
                    }

                    // Verifikasi
                    verifyBtn.classList.toggle('hidden', !actions.can_verify);

                    // Tolak
                    rejectBtn.classList.toggle('hidden', !actions.can_reject);

                    // Toggle aktif
                    if (actions.can_toggle_active) {
                        toggleActiveBtn.classList.remove('hidden');
                        document.getElementById('toggle-active-text').textContent = isActive ?
                            'Nonaktifkan' : 'Aktifkan';
                    } else {
                        toggleActiveBtn.classList.add('hidden');
                    }

                    // Hapus
                    deleteBtn.classList.toggle('hidden', !actions.can_delete);

                    dropdownContainer.classList.remove('hidden');

                } else if (!dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });

            // ── Hapus ───────────────────────────────────────────
            deleteBtn.addEventListener('click', function() {
                if (!currentDropdownData) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                document.getElementById('modal-mustahik-name').textContent = currentDropdownData.name;
                document.getElementById('delete-form').action = `/mustahik/${currentMustahikUuid}`;
                document.getElementById('delete-modal').classList.remove('hidden');
            });

            document.getElementById('cancel-delete-btn').addEventListener('click', function() {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // ── Verifikasi ──────────────────────────────────────
            verifyBtn.addEventListener('click', function() {
                if (!currentMustahikUuid) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                if (confirm('Verifikasi mustahik ini?')) {
                    verifyMustahik(currentMustahikUuid);
                }
            });

            // ── Tolak ───────────────────────────────────────────
            rejectBtn.addEventListener('click', function() {
                if (!currentDropdownData) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                document.getElementById('modal-reject-mustahik-name').textContent = currentDropdownData
                .name;
                document.getElementById('alasan_penolakan').value = '';
                document.getElementById('reject-modal').classList.remove('hidden');
            });

            document.getElementById('confirm-reject-btn').addEventListener('click', function() {
                if (!currentMustahikUuid) return;
                const alasan = document.getElementById('alasan_penolakan').value.trim();
                if (!alasan) {
                    ToastNotification.show('Harap masukkan alasan penolakan', 'warning');
                    return;
                }
                rejectMustahik(currentMustahikUuid, alasan);
            });

            document.getElementById('cancel-reject-btn').addEventListener('click', function() {
                document.getElementById('reject-modal').classList.add('hidden');
            });

            document.getElementById('reject-modal').addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // ── Toggle Aktif ────────────────────────────────────
            toggleActiveBtn.addEventListener('click', function() {
                if (!currentMustahikUuid) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                const actionText = currentDropdownData.isActive ? 'Nonaktifkan' : 'Aktifkan';
                if (confirm(`${actionText} mustahik ini?`)) {
                    toggleActiveMustahik(currentMustahikUuid);
                }
            });

            // ── Tutup dropdown saat scroll / resize ─────────────
            const hideDropdown = () => {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            };

            window.addEventListener('scroll', hideDropdown, true);
            window.addEventListener('resize', hideDropdown);
            if (tableContainer) tableContainer.addEventListener('scroll', hideDropdown, true);
        });

        // ── Search toggle ───────────────────────────────────────
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
                if (!'{{ request('q') }}') searchInput.value = '';
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        // ── Filter panel toggle ─────────────────────────────────
        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }

        // ── Remove filter dari URL ──────────────────────────────
        function removeFilter(filterName) {
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        // ── API: Verifikasi ─────────────────────────────────────
        function verifyMustahik(uuid) {
            fetch(`/mustahik/${uuid}/verify`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    ToastNotification.show(data.message, data.success ? 'success' : 'error');
                    if (data.success) setTimeout(() => location.reload(), 1500);
                })
                .catch(() => ToastNotification.show('Terjadi kesalahan saat memverifikasi', 'error'));
        }

        // ── API: Tolak ──────────────────────────────────────────
        function rejectMustahik(uuid, alasan) {
            fetch(`/mustahik/${uuid}/reject`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        alasan_penolakan: alasan
                    })
                })
                .then(r => r.json())
                .then(data => {
                    ToastNotification.show(data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        document.getElementById('reject-modal').classList.add('hidden');
                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(() => ToastNotification.show('Terjadi kesalahan saat menolak', 'error'));
        }

        // ── API: Toggle Aktif ───────────────────────────────────
        function toggleActiveMustahik(uuid) {
            fetch(`/mustahik/${uuid}/toggle-active`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    ToastNotification.show(data.message, data.success ? 'success' : 'error');
                    if (data.success) setTimeout(() => location.reload(), 1500);
                })
                .catch(() => ToastNotification.show('Terjadi kesalahan saat mengubah status', 'error'));
        }
    </script>
@endpush
