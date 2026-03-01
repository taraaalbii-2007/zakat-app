{{-- resources/views/superadmin/kontak/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pesan Masuk')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

            {{-- Header --}}
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Pesan Masuk</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $kontaks->total() }} pesan</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        {{-- Filter toggle --}}
                        <button type="button" onclick="toggleFilter()"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        </button>

                        {{-- Search --}}
                        <div id="search-container">
                            <button type="button" onclick="toggleSearch()" id="search-button"
                                class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all w-full sm:w-auto {{ request('q') ? 'hidden' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="hidden sm:inline-block sm:ml-2">Cari</span>
                            </button>
                            <form method="GET" action="{{ route('superadmin.kontak.index') }}" id="search-form"
                                class="{{ request('q') ? '' : 'hidden' }}">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div class="flex items-center">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="search" name="q" value="{{ request('q') }}" id="search-input"
                                            placeholder="Cari nama, email, subjek..."
                                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all"
                                            style="min-width:240px">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Bar --}}
            <div class="px-4 sm:px-6 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex flex-wrap gap-3 sm:gap-6 text-xs">
                    <a href="{{ route('superadmin.kontak.index') }}"
                        class="inline-flex items-center gap-1.5 font-medium {{ !request('status') ? 'text-primary' : 'text-gray-500 hover:text-gray-700' }}">
                        <span class="w-2 h-2 rounded-full bg-gray-400 inline-block"></span>
                        Semua
                    </a>
                    <a href="{{ route('superadmin.kontak.index', ['status' => 'baru']) }}"
                        class="inline-flex items-center gap-1.5 font-medium {{ request('status') === 'baru' ? 'text-amber-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                        Baru
                        @if ($totalBaru > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                {{ $totalBaru }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('superadmin.kontak.index', ['status' => 'dibaca']) }}"
                        class="inline-flex items-center gap-1.5 font-medium {{ request('status') === 'dibaca' ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-400 inline-block"></span>
                        Dibaca
                        @if ($totalDibaca > 0)
                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                {{ $totalDibaca }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('superadmin.kontak.index', ['status' => 'dibalas']) }}"
                        class="inline-flex items-center gap-1.5 font-medium {{ request('status') === 'dibalas' ? 'text-emerald-600' : 'text-gray-500 hover:text-gray-700' }}">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                        Dibalas
                    </a>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel"
                class="{{ request('status') ? '' : 'hidden' }} px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
                <form method="GET" action="{{ route('superadmin.kontak.index') }}">
                    @if(request('q'))
                        <input type="hidden" name="q" value="{{ request('q') }}">
                    @endif
                    <div class="flex flex-wrap items-center gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select name="status"
                                class="block px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary"
                                onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="baru"    {{ request('status') === 'baru'    ? 'selected' : '' }}>Baru</option>
                                <option value="dibaca"  {{ request('status') === 'dibaca'  ? 'selected' : '' }}>Dibaca</option>
                                <option value="dibalas" {{ request('status') === 'dibalas' ? 'selected' : '' }}>Dibalas</option>
                            </select>
                        </div>
                        @if(request('status'))
                            <div class="mt-4">
                                <a href="{{ route('superadmin.kontak.index', request('q') ? ['q' => request('q')] : []) }}"
                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Alert --}}
            @if (session('success'))
                <div class="mx-4 sm:mx-6 mt-4 rounded-xl bg-emerald-50 border border-emerald-200 p-3 flex items-center gap-2 text-sm text-emerald-800">
                    <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($kontaks->count() > 0)
                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjek</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($kontaks as $kontak)
                                <tr class="hover:bg-gray-50 transition-colors {{ is_null($kontak->dibaca_at) ? 'bg-amber-50/30' : '' }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-sm font-bold text-primary">
                                                {{ strtoupper(substr($kontak->nama, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 {{ is_null($kontak->dibaca_at) ? 'font-bold' : '' }}">
                                                    {{ $kontak->nama }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $kontak->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-gray-800 font-medium truncate max-w-xs">{{ $kontak->subjek }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($kontak->pesan, 80) }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700">{{ $kontak->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $kontak->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {!! $kontak->status_badge !!}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('superadmin.kontak.show', $kontak) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors border border-primary/20">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Buka
                                            </a>
                                            <button type="button"
                                                data-uuid="{{ $kontak->id }}"
                                                data-nama="{{ $kontak->nama }}"
                                                class="delete-btn inline-flex items-center px-2 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
                    @foreach ($kontaks as $kontak)
                        <div class="p-4 hover:bg-gray-50 transition-colors {{ is_null($kontak->dibaca_at) ? 'border-l-4 border-l-amber-400' : '' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-sm font-bold text-primary">
                                        {{ strtoupper(substr($kontak->nama, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-semibold text-gray-900">{{ $kontak->nama }}</p>
                                            {!! $kontak->status_badge !!}
                                        </div>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $kontak->email }}</p>
                                        <p class="text-sm font-medium text-gray-800 mt-1 truncate">{{ $kontak->subjek }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $kontak->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('superadmin.kontak.show', $kontak) }}"
                                    class="flex-shrink-0 inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary border border-primary/20 rounded-lg hover:bg-primary/5 transition-colors">
                                    Buka
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($kontaks->hasPages())
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                        {{ $kontaks->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pesan</h3>
                    <p class="text-sm text-gray-500">
                        @if(request('q') || request('status'))
                            Tidak ada pesan yang sesuai dengan pencarian atau filter.
                        @else
                            Belum ada pesan masuk dari pengguna.
                        @endif
                    </p>
                    @if(request('q') || request('status'))
                        <a href="{{ route('superadmin.kontak.index') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            Reset Pencarian
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="delete-modal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl sm:rounded-2xl bg-white">
            <div class="flex justify-center mb-4">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Pesan</h3>
            <p class="text-sm text-gray-500 mb-1 text-center">
                Apakah Anda yakin ingin menghapus pesan dari
                "<span id="modal-nama" class="font-semibold text-gray-700"></span>"?
            </p>
            <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex justify-center gap-3">
                <button type="button" id="cancel-delete"
                    class="w-28 rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="button" id="confirm-delete"
                    class="w-28 rounded-lg shadow-sm px-4 py-2.5 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentUuid = null;

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            currentUuid = this.dataset.uuid;
            document.getElementById('modal-nama').textContent = this.dataset.nama;
            document.getElementById('delete-modal').classList.remove('hidden');
        });
    });

    document.getElementById('cancel-delete').addEventListener('click', function () {
        document.getElementById('delete-modal').classList.add('hidden');
    });

    document.getElementById('confirm-delete').addEventListener('click', function () {
        if (!currentUuid) return;
        const form    = document.createElement('form');
        form.method   = 'POST';
        form.action   = `/superadmin/kontak/${currentUuid}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    });

    document.getElementById('delete-modal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    function toggleSearch() {
        const btn  = document.getElementById('search-button');
        const form = document.getElementById('search-form');
        const inp  = document.getElementById('search-input');
        if (form.classList.contains('hidden')) {
            btn.classList.add('hidden');
            form.classList.remove('hidden');
            setTimeout(() => inp.focus(), 50);
        } else {
            form.classList.add('hidden');
            btn.classList.remove('hidden');
        }
    }

    function toggleFilter() {
        document.getElementById('filter-panel').classList.toggle('hidden');
    }
</script>
@endpush