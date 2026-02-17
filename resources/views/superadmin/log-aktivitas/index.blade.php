@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem')

@section('content')
<div class="space-y-6">

    {{-- Main Content --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Log Aktivitas</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Rekam jejak aktivitas pengguna dalam sistem</p>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Filter Tabs --}}
                    <div class="hidden sm:flex items-center gap-1.5 p-1 bg-gray-100 rounded-lg">
                        <a href="{{ route('log-aktivitas.index') }}" 
                           class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ !request('aktivitas') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                            Semua
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'login']) }}" 
                           class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ request('aktivitas') == 'login' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                            Login
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'create']) }}" 
                           class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ request('aktivitas') == 'create' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                            Tambah
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'update']) }}" 
                           class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ request('aktivitas') == 'update' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                            Edit
                        </a>
                        <a href="{{ route('log-aktivitas.index', ['aktivitas' => 'delete']) }}" 
                           class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors {{ request('aktivitas') == 'delete' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900' }}">
                            Hapus
                        </a>
                    </div>

                    {{-- Filter Button --}}
                    <button type="button" onclick="toggleFilter()" 
                            class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span>Filter</span>
                        @if(request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']))
                            <span class="w-2 h-2 bg-primary rounded-full"></span>
                        @endif
                    </button>
                </div>
            </div>

            {{-- Filter Panel --}}
            <div id="filter-panel" class="{{ request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']) ? '' : 'hidden' }} mt-4 pt-4 border-t border-gray-100">
                <form method="GET" action="{{ route('log-aktivitas.index') }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Pencarian</label>
                            <input type="text" name="q" value="{{ request('q') }}" 
                                   placeholder="Cari aktivitas..."
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Aktivitas</label>
                            <select name="aktivitas" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                <option value="">Semua Aktivitas</option>
                                @foreach($aktivitasList as $akt)
                                    <option value="{{ $akt }}" {{ request('aktivitas') == $akt ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $akt)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Modul</label>
                            <select name="modul" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                <option value="">Semua Modul</option>
                                @foreach($modulList as $mod)
                                    <option value="{{ $mod }}" {{ request('modul') == $mod ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $mod)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Peran</label>
                            <select name="peran" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                                <option value="">Semua Peran</option>
                                @foreach($peranList as $per)
                                    <option value="{{ $per }}" {{ request('peran') == $per ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $per)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 mt-4">
                        @if(request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']))
                            <a href="{{ route('log-aktivitas.index') }}" 
                               class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
                                Reset
                            </a>
                        @endif
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-primary hover:bg-primary-600 rounded-lg transition-colors">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($logs->count() > 0)
            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-12">No</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengguna</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aktivitas</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Modul</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-40">Waktu</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($logs as $index => $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-500">{{ ($logs->currentPage() - 1) * $logs->perPage() + $index + 1 }}</span>
                                </td>

                                <td class="px-5 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $log->nama_pengguna }}</p>
                                        @if($log->email_pengguna)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $log->email_pengguna }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $log->peran) }}</p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $log->badge_color }} capitalize">
                                        {{ str_replace('_', ' ', $log->aktivitas) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $log->modul) }}</span>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-gray-900">{{ Str::limit($log->deskripsi ?? '-', 50) }}</p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-gray-900">{{ $log->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</p>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('log-aktivitas.show', $log->uuid) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-gray-400 hover:text-primary hover:bg-primary-50 transition-colors"
                                       title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            {{-- Mobile Cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @foreach ($logs as $log)
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $log->nama_pengguna }}</p>
                                @if($log->email_pengguna)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $log->email_pengguna }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-0.5 capitalize">{{ str_replace('_', ' ', $log->peran) }}</p>
                            </div>
                            <span class="text-xs text-gray-500 whitespace-nowrap">{{ $log->created_at->format('H:i') }}</span>
                        </div>

                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-medium {{ $log->badge_color }} capitalize">
                                {{ str_replace('_', ' ', $log->aktivitas) }}
                            </span>
                            <span class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $log->modul) }}</span>
                        </div>

                        <p class="text-sm text-gray-600">{{ $log->deskripsi ?? 'Tidak ada deskripsi' }}</p>

                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                @if($log->ip_address)
                                    <span class="font-mono">{{ $log->ip_address }}</span>
                                @endif
                                <span>{{ $log->created_at->format('d/m/Y') }}</span>
                            </div>
                            <a href="{{ route('log-aktivitas.show', $log->uuid) }}" 
                               class="text-xs font-medium text-primary hover:text-primary-600">
                                Detail →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($logs->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $logs->links() }}
                </div>
            @endif
        @else
            <div class="px-5 py-16 text-center">
                @if(request()->anyFilled(['q', 'aktivitas', 'modul', 'tanggal', 'peran']))
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Hasil</h3>
                    <p class="text-sm text-gray-500 mb-4">Tidak ditemukan log yang sesuai dengan filter.</p>
                    <a href="{{ route('log-aktivitas.index') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filter
                    </a>
                @else
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Aktivitas</h3>
                    <p class="text-sm text-gray-500">Log aktivitas sistem akan muncul di sini.</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFilter() {
    const panel = document.getElementById('filter-panel');
    panel.classList.toggle('hidden');
}
</script>
@endpush