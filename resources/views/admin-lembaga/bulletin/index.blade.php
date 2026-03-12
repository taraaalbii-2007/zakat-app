@extends('layouts.app')

@section('title', 'Bulletin Saya')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Bulletin Lembaga Saya</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Total: {{ $bulletins->total() }} Bulletin</p>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Filter --}}
                    <button type="button" onclick="toggleFilter()" id="filter-button"
                            class="group inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Filter</span>
                        @if(request('q') || request('status') || request('kategori'))
                            <span class="ml-1.5 inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-primary rounded-full">
                                {{ collect([request('q'), request('status'), request('kategori')])->filter()->count() }}
                            </span>
                        @endif
                    </button>

                    {{-- Buat Bulletin --}}
                    <a href="{{ route('admin-lembaga.bulletin.create') }}"
                       class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="hidden sm:inline-block sm:ml-2">Buat Bulletin</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Status Summary --}}
        <div class="px-4 sm:px-6 py-3 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin-lembaga.bulletin.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ !request('status') ? 'bg-gray-800 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Semua <span class="font-bold">{{ $counts['all'] }}</span>
                </a>
                <a href="{{ route('admin-lembaga.bulletin.index', ['status' => 'draft']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ request('status') === 'draft' ? 'bg-gray-500 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Draft <span class="font-bold">{{ $counts['draft'] }}</span>
                </a>
                <a href="{{ route('admin-lembaga.bulletin.index', ['status' => 'pending']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Menunggu <span class="font-bold">{{ $counts['pending'] }}</span>
                </a>
                <a href="{{ route('admin-lembaga.bulletin.index', ['status' => 'approved']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ request('status') === 'approved' ? 'bg-green-500 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Disetujui <span class="font-bold">{{ $counts['approved'] }}</span>
                </a>
                <a href="{{ route('admin-lembaga.bulletin.index', ['status' => 'rejected']) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium transition-colors
                          {{ request('status') === 'rejected' ? 'bg-red-500 text-white' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                    Ditolak <span class="font-bold">{{ $counts['rejected'] }}</span>
                </a>
            </div>
        </div>

        {{-- Filter Panel --}}
        <div id="filter-panel" class="{{ request('q') || request('status') || request('kategori') ? '' : 'hidden' }} border-b border-gray-200 bg-gray-50">
            <form method="GET" action="{{ route('admin-lembaga.bulletin.index') }}" class="p-4 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Cari Bulletin</label>
                        <input type="search" name="q" value="{{ request('q') }}"
                               placeholder="Cari judul..."
                               class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="kategori"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <a href="{{ route('admin-lembaga.bulletin.index') }}"
                           class="flex-1 px-4 py-2 text-sm text-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Reset
                        </a>
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-primary text-white text-sm rounded-lg hover:bg-primary-600 transition-colors">
                            Terapkan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($bulletins->count() > 0)

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulletin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bulletins as $bulletin)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-md bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                            @if($bulletin->thumbnail)
                                                <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('admin-lembaga.bulletin.show', $bulletin->uuid) }}"
                                               class="text-sm font-medium text-gray-800 hover:text-primary-600 line-clamp-2 leading-snug">
                                                {{ $bulletin->judul }}
                                            </a>
                                            {{-- Alasan penolakan --}}
                                            @if($bulletin->isRejected() && $bulletin->rejection_reason)
                                                <p class="mt-1 text-xs text-red-600 line-clamp-1">
                                                    <span class="font-medium">Alasan:</span> {{ $bulletin->rejection_reason }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($bulletin->kategoriBulletin)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700">
                                            {{ $bulletin->kategoriBulletin->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusConfig = [
                                            'draft'    => ['bg-gray-100 text-gray-700',   'Draft'],
                                            'pending'  => ['bg-yellow-100 text-yellow-700','Menunggu'],
                                            'approved' => ['bg-green-100 text-green-700',  'Disetujui'],
                                            'rejected' => ['bg-red-100 text-red-700',      'Ditolak'],
                                        ];
                                        [$cls, $label] = $statusConfig[$bulletin->status] ?? ['bg-gray-100 text-gray-700', $bulletin->status];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $cls }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-xs text-gray-900">
                                        {{ $bulletin->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $bulletin->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        {{-- Lihat --}}
                                        <a href="{{ route('admin-lembaga.bulletin.show', $bulletin->uuid) }}"
                                           class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        {{-- Edit (hanya draft/rejected) --}}
                                        @if($bulletin->isEditable())
                                            <a href="{{ route('admin-lembaga.bulletin.edit', $bulletin->uuid) }}"
                                               class="p-1.5 text-gray-400 hover:text-primary-600 hover:bg-primary-50 rounded-md transition-colors"
                                               title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        {{-- Submit (draft/rejected) --}}
                                        @if($bulletin->isDraft() || $bulletin->isRejected())
                                            <form method="POST" action="{{ route('admin-lembaga.bulletin.submit', $bulletin->uuid) }}" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-md transition-colors"
                                                        title="Kirim untuk Persetujuan"
                                                        onclick="return confirm('Kirim bulletin ini untuk persetujuan superadmin?')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        {{-- Hapus (bukan approved) --}}
                                        @if(!$bulletin->isApproved())
                                            <button type="button"
                                                    onclick="confirmDelete('{{ $bulletin->uuid }}', '{{ addslashes($bulletin->judul) }}')"
                                                    class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                                    title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($bulletins as $bulletin)
                    <div class="p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-2">
                            <div class="w-8 h-8 rounded-md bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-200">
                                @if($bulletin->thumbnail)
                                    <img src="{{ Storage::url($bulletin->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('admin-lembaga.bulletin.show', $bulletin->uuid) }}"
                                   class="text-xs font-medium text-gray-800 line-clamp-2">{{ $bulletin->judul }}</a>
                                @if($bulletin->isRejected() && $bulletin->rejection_reason)
                                    <p class="mt-0.5 text-[10px] text-red-600 line-clamp-1">Alasan: {{ $bulletin->rejection_reason }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    @php
                                        $statusConfig = [
                                            'draft'    => 'bg-gray-100 text-gray-700',
                                            'pending'  => 'bg-yellow-100 text-yellow-700',
                                            'approved' => 'bg-green-100 text-green-700',
                                            'rejected' => 'bg-red-100 text-red-700',
                                        ];
                                        $labels = ['draft'=>'Draft','pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak'];
                                    @endphp
                                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-semibold {{ $statusConfig[$bulletin->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $labels[$bulletin->status] ?? $bulletin->status }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">{{ $bulletin->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1">
                                <a href="{{ route('admin-lembaga.bulletin.show', $bulletin->uuid) }}"
                                   class="p-1 text-gray-400 hover:text-blue-600 rounded-md transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($bulletin->isEditable())
                                    <a href="{{ route('admin-lembaga.bulletin.edit', $bulletin->uuid) }}"
                                       class="p-1 text-gray-400 hover:text-primary-600 rounded-md transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($bulletins->hasPages())
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
                    {{ $bulletins->links() }}
                </div>
            @endif

        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h3 class="text-base font-medium text-gray-900 mb-2">Belum Ada Bulletin</h3>
                <p class="text-sm text-gray-500 mb-6">Mulai dengan membuat bulletin pertama lembaga Anda.</p>
                <a href="{{ route('admin-lembaga.bulletin.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Buat Bulletin
                </a>
            </div>
        @endif
    </div>

    {{-- Info Box Alur --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">Alur Publikasi Bulletin</p>
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="px-2 py-1 bg-gray-200 text-gray-700 rounded-full">Draft</span>
                    <span>→</span>
                    <span class="px-2 py-1 bg-yellow-200 text-yellow-700 rounded-full">Menunggu Persetujuan</span>
                    <span>→</span>
                    <span class="px-2 py-1 bg-green-200 text-green-700 rounded-full">Disetujui (Tampil di Website)</span>
                </div>
                <p class="mt-1.5 text-xs">Bulletin yang ditolak dapat diedit ulang dan diajukan kembali.</p>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="p-4 sm:p-6 border border-gray-200 w-full max-w-sm shadow-lg rounded-xl bg-white">
        <div class="flex justify-center mb-4">
            <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Hapus Bulletin</h3>
        <p class="text-sm text-gray-500 mb-1 text-center">
            Apakah Anda yakin ingin menghapus "<span id="modal-bulletin-name" class="font-semibold text-gray-700"></span>"?
        </p>
        <p class="text-sm text-gray-500 mb-6 text-center">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <form id="delete-form" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-5 py-2 rounded-lg bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFilter() {
    document.getElementById('filter-panel').classList.toggle('hidden');
}

function confirmDelete(uuid, judul) {
    document.getElementById('modal-bulletin-name').textContent = judul;
    document.getElementById('delete-form').action = '/bulletin-saya/' + uuid;
    document.getElementById('delete-modal').classList.remove('hidden');
}
</script>
@endpush