{{-- resources/views/admin-masjid/program/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Program Zakat')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Daftar Program Zakat</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $programs->total() }} Program</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('program-zakat.create') }}"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2 group-hover:inline-block transition-all duration-300">
                                Buat Program
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
                            <form method="GET" action="{{ route('program-zakat.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('tahun'))
                                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
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
                                            placeholder="Cari nama program, kode..."
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
                class="{{ (request('status') || request('tahun')) ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('program-zakat.index') }}" id="filter-form">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="filter-status"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="tahun" id="filter-tahun"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(request('status') || request('tahun'))
                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('program-zakat.index', request('q') ? ['q' => request('q')] : []) }}"
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

            @if ($programs->count() > 0)
                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Program</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Target</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Progress</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($programs as $program)
                                @php
                                    $canEdit   = !in_array($program->status, ['selesai', 'dibatalkan']);
                                    $canDelete = $program->status === 'draft';
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $program->nama_program }}</div>
                                        <div class="text-xs text-gray-500">{{ $program->kode_program }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $program->tanggal_mulai->format('d M Y') }}
                                        </div>
                                        @if($program->tanggal_selesai)
                                            <div class="text-xs text-gray-500">
                                                s/d {{ $program->tanggal_selesai->format('d M Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            @if($program->target_dana)
                                                Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                        @if($program->target_mustahik)
                                            <div class="text-xs text-gray-500">
                                                {{ $program->target_mustahik }} Mustahik
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($program->target_dana)
                                            <div class="space-y-1">
                                                <div class="flex items-center text-xs text-gray-600">
                                                    <span class="mr-2">Dana:</span>
                                                    <span class="font-medium">{{ $program->progress_dana }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-primary h-1.5 rounded-full"
                                                        style="width: {{ $program->progress_dana }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($program->target_mustahik)
                                            <div class="space-y-1 mt-2">
                                                <div class="flex items-center text-xs text-gray-600">
                                                    <span class="mr-2">Mustahik:</span>
                                                    <span class="font-medium">{{ $program->progress_mustahik }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-green-500 h-1.5 rounded-full"
                                                        style="width: {{ $program->progress_mustahik }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {!! $program->status_badge !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="relative inline-block text-left">
                                            <button type="button"
                                                data-dropdown-toggle="{{ $program->uuid }}"
                                                data-nama="{{ $program->nama_program }}"
                                                data-can-edit="{{ $canEdit ? '1' : '0' }}"
                                                data-can-delete="{{ $canDelete ? '1' : '0' }}"
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
                    @foreach ($programs as $program)
                        @php
                            $canEdit   = !in_array($program->status, ['selesai', 'dibatalkan']);
                            $canDelete = $program->status === 'draft';
                        @endphp
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 mb-0.5">
                                        {{ $program->nama_program }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mb-2">{{ $program->kode_program }}</p>

                                    <div class="flex flex-wrap gap-1.5 mb-3">
                                        {!! $program->status_badge !!}
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex items-center text-xs text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $program->tanggal_mulai->format('d M Y') }}
                                            @if($program->tanggal_selesai)
                                                &mdash; {{ $program->tanggal_selesai->format('d M Y') }}
                                            @endif
                                        </div>

                                        @if($program->target_dana)
                                            <div class="space-y-1">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-600">
                                                        Dana: Rp {{ number_format($program->realisasi_dana, 0, ',', '.') }}
                                                        / Rp {{ number_format($program->target_dana, 0, ',', '.') }}
                                                    </span>
                                                    <span class="font-medium text-primary">
                                                        {{ $program->progress_dana }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-primary h-1.5 rounded-full"
                                                        style="width: {{ $program->progress_dana }}%"></div>
                                                </div>
                                            </div>
                                        @endif

                                        @if($program->target_mustahik)
                                            <div class="space-y-1">
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-600">
                                                        Mustahik: {{ $program->realisasi_mustahik }}
                                                        / {{ $program->target_mustahik }}
                                                    </span>
                                                    <span class="font-medium text-green-600">
                                                        {{ $program->progress_mustahik }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-green-500 h-1.5 rounded-full"
                                                        style="width: {{ $program->progress_mustahik }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <button type="button"
                                    data-dropdown-toggle="{{ $program->uuid }}"
                                    data-nama="{{ $program->nama_program }}"
                                    data-can-edit="{{ $canEdit ? '1' : '0' }}"
                                    data-can-delete="{{ $canDelete ? '1' : '0' }}"
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

                @if ($programs->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $programs->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 sm:p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    @if(request('q') || request('status') || request('tahun'))
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Data Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6">
                            @if(request('q'))
                                Tidak ada program yang cocok dengan "{{ request('q') }}"
                            @else
                                Tidak ada program yang sesuai dengan filter yang dipilih
                            @endif
                        </p>
                        <a href="{{ route('program-zakat.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Pencarian
                        </a>
                    @else
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Program Zakat</h3>
                        <p class="text-sm text-gray-500 mb-6">Mulai buat program zakat untuk mengelola penyaluran dana
                            zakat.</p>
                        <a href="{{ route('program-zakat.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Buat Program
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Dropdown Container (Global — satu untuk semua baris) --}}
    <div id="dropdown-container" class="fixed hidden z-50">
        <div class="w-44 sm:w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1">
                {{-- Detail — selalu tampil --}}
                <a href="#" id="dropdown-detail-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat Detail
                </a>

                {{-- Edit — hanya jika status bukan selesai/dibatalkan --}}
                <a href="#" id="dropdown-edit-link"
                    class="flex items-center px-3 sm:px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors hidden">
                    <svg class="w-4 h-4 mr-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit
                </a>

                {{-- Hapus — hanya jika status draft --}}
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
            <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-1.5 sm:mb-2 text-center">Hapus Program</h3>
            <p class="text-xs sm:text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus program
                "<span id="modal-program-name" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-xs sm:text-sm text-gray-500 mb-5 sm:mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-2 sm:gap-3">
                <button type="button" id="cancel-delete-btn"
                    class="w-24 sm:w-28 rounded-lg border border-gray-300 shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-white text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete-btn"
                    class="w-24 sm:w-28 rounded-lg shadow-sm px-3 sm:px-4 py-2 sm:py-2.5 bg-red-600 text-xs sm:text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentProgramUuid = null;
        let currentProgramName = null;

        document.addEventListener('DOMContentLoaded', function () {
            const dropdownContainer = document.getElementById('dropdown-container');
            const detailLink        = document.getElementById('dropdown-detail-link');
            const editLink          = document.getElementById('dropdown-edit-link');
            const deleteBtn         = document.getElementById('dropdown-delete-btn');
            const tableContainer    = document.getElementById('table-container');

            // ── Buka / Tutup Dropdown ─────────────────────────────────────────
            document.addEventListener('click', function (e) {
                const toggle = e.target.closest('.dropdown-toggle');

                if (toggle) {
                    e.stopPropagation();

                    const uuid     = toggle.getAttribute('data-dropdown-toggle');
                    const nama     = toggle.getAttribute('data-nama');
                    const canEdit  = toggle.getAttribute('data-can-edit') === '1';
                    const canDelete = toggle.getAttribute('data-can-delete') === '1';

                    // Klik tombol yang sama → toggle tutup
                    if (dropdownContainer.getAttribute('data-current-uuid') === uuid &&
                        !dropdownContainer.classList.contains('hidden')) {
                        dropdownContainer.classList.add('hidden');
                        dropdownContainer.removeAttribute('data-current-uuid');
                        return;
                    }

                    currentProgramUuid = uuid;
                    currentProgramName = nama;
                    dropdownContainer.setAttribute('data-current-uuid', uuid);

                    // Hitung posisi (collision detection kanan & bawah)
                    const rect         = toggle.getBoundingClientRect();
                    const dropdownW    = window.innerWidth < 640 ? 176 : 192;
                    const dropdownH    = 160;
                    let top  = rect.bottom + window.scrollY;
                    let left = rect.left   + window.scrollX;

                    if (rect.left + dropdownW > window.innerWidth) {
                        left = window.innerWidth - dropdownW - 10 + window.scrollX;
                    }
                    if (rect.bottom + dropdownH > window.innerHeight) {
                        top = rect.top - dropdownH + window.scrollY;
                    }

                    dropdownContainer.style.top  = top  + 'px';
                    dropdownContainer.style.left = left + 'px';

                    // Set link
                    detailLink.href = `/program-zakat/${uuid}`;

                    if (canEdit) {
                        editLink.href = `/program-zakat/${uuid}/edit`;
                        editLink.classList.remove('hidden');
                    } else {
                        editLink.classList.add('hidden');
                    }

                    if (canDelete) {
                        deleteBtn.classList.remove('hidden');
                    } else {
                        deleteBtn.classList.add('hidden');
                    }

                    dropdownContainer.classList.remove('hidden');

                } else if (!dropdownContainer.contains(e.target)) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            });

            // ── Hapus ─────────────────────────────────────────────────────────
            deleteBtn.addEventListener('click', function () {
                if (!currentProgramUuid) return;
                dropdownContainer.classList.add('hidden');
                dropdownContainer.removeAttribute('data-current-uuid');

                document.getElementById('modal-program-name').textContent = currentProgramName;
                document.getElementById('delete-modal').classList.remove('hidden');
            });

            document.getElementById('confirm-delete-btn').addEventListener('click', function () {
                if (!currentProgramUuid) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/program-zakat/${currentProgramUuid}`;

                const csrf = document.createElement('input');
                csrf.type  = 'hidden';
                csrf.name  = '_token';
                csrf.value = '{{ csrf_token() }}';

                const method = document.createElement('input');
                method.type  = 'hidden';
                method.name  = '_method';
                method.value = 'DELETE';

                form.appendChild(csrf);
                form.appendChild(method);
                document.body.appendChild(form);
                form.submit();
            });

            document.getElementById('cancel-delete-btn').addEventListener('click', function () {
                document.getElementById('delete-modal').classList.add('hidden');
            });

            document.getElementById('delete-modal').addEventListener('click', function (e) {
                if (e.target === this) this.classList.add('hidden');
            });

            // ── Tutup dropdown saat scroll / resize ──────────────────────────
            const closeDropdown = () => {
                if (!dropdownContainer.classList.contains('hidden')) {
                    dropdownContainer.classList.add('hidden');
                    dropdownContainer.removeAttribute('data-current-uuid');
                }
            };

            window.addEventListener('scroll', closeDropdown, true);
            window.addEventListener('resize', closeDropdown);

            if (tableContainer) {
                tableContainer.addEventListener('scroll', closeDropdown, true);
            }
        });

        // ── Search & Filter helpers ───────────────────────────────────────────
        function toggleSearch() {
            const searchButton    = document.getElementById('search-button');
            const searchForm      = document.getElementById('search-form');
            const searchInput     = document.getElementById('search-input');
            const searchContainer = document.getElementById('search-container');

            if (searchForm.classList.contains('hidden')) {
                searchButton.classList.add('hidden');
                searchForm.classList.remove('hidden');
                searchContainer.style.minWidth = '280px';
                setTimeout(() => searchInput.focus(), 50);
            } else {
                const hasQuery = '{{ request('q') }}' !== '';
                if (!hasQuery) searchInput.value = '';
                searchForm.classList.add('hidden');
                searchButton.classList.remove('hidden');
                searchContainer.style.minWidth = 'auto';
            }
        }

        function toggleFilter() {
            document.getElementById('filter-panel').classList.toggle('hidden');
        }
    </script>
@endpush