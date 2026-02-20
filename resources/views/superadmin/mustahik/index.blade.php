@extends('layouts.app')

@section('title', 'Data Mustahik Semua Masjid')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Data Mustahik Semua Masjid</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ $totalMustahik }} Mustahik dari {{ $masjids->count() }} Masjid
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <button type="button" onclick="expandAll()"
                            class="inline-flex items-center justify-center px-3 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                            Buka Semua
                        </button>
                        <button type="button" onclick="collapseAll()"
                            class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                            </svg>
                            Tutup Semua
                        </button>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="search" placeholder="Cari nama masjid..."
                                oninput="filterMasjid(this.value)"
                                class="pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all w-full sm:w-56">
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="w-10 px-4 py-3"></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masjid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Alamat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Mustahik</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($masjids as $masjid)
                            <tr class="masjid-row cursor-pointer hover:bg-primary/5 transition-colors"
                                data-nama="{{ strtolower($masjid->nama) }}"
                                onclick="toggleMasjid('mustahik-masjid-{{ $masjid->id }}', this)">
                                <td class="px-4 py-3">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 masjid-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $masjid->nama }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat mustahik</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 hidden md:table-cell">
                                    <div class="text-sm text-gray-600">{{ Str::limit($masjid->alamat ?? '-', 50) }}</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        {{ $masjid->mustahiks->count() }} Mustahik
                                    </span>
                                </td>
                            </tr>

                            {{-- Expandable Row --}}
                            <tr id="mustahik-masjid-{{ $masjid->id }}" class="hidden masjid-content-row">
                                <td colspan="4" class="p-0">
                                    <div class="bg-gradient-to-b from-green-50/50 to-gray-50 border-y border-green-200/50 px-6 py-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Daftar Mustahik — {{ $masjid->nama }}
                                            </h3>
                                        </div>

                                        @if ($masjid->mustahiks->isEmpty())
                                            <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                Belum ada data mustahik untuk masjid ini
                                            </div>
                                        @else
                                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-white">
                                                        <tr>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">No. Registrasi</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Mustahik</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kategori</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-100">
                                                        @foreach ($masjid->mustahiks as $mustahik)
                                                            <tr class="hover:bg-gray-50 transition-colors">
                                                                <td class="px-4 py-3">
                                                                    <div class="text-sm font-medium text-gray-900">{{ $mustahik->no_registrasi }}</div>
                                                                    <div class="text-xs text-gray-400">{{ $mustahik->tanggal_registrasi->format('d M Y') }}</div>
                                                                </td>
                                                                <td class="px-4 py-3">
                                                                    <div class="flex items-center gap-2">
                                                                        <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                                                            <span class="text-xs font-semibold text-primary">{{ strtoupper(substr($mustahik->nama_lengkap, 0, 1)) }}</span>
                                                                        </div>
                                                                        <div>
                                                                            <div class="text-sm font-medium text-gray-900">{{ $mustahik->nama_lengkap }}</div>
                                                                            @if($mustahik->nik)
                                                                                <div class="text-xs text-gray-400">NIK: {{ $mustahik->nik }}</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 hidden sm:table-cell">
                                                                    @if($mustahik->kategoriMustahik)
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                            {{ $mustahik->kategoriMustahik->nama }}
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                <td class="px-4 py-3">
                                                                    <div class="space-y-1">
                                                                        @if($mustahik->status_verifikasi == 'verified')
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                                                        @elseif($mustahik->status_verifikasi == 'pending')
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                                                        @else
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                                                        @endif
                                                                        @if($mustahik->is_active)
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Aktif</span>
                                                                        @else
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Nonaktif</span>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">
                                    Belum ada data masjid
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleMasjid(id, row) {
        const content = document.getElementById(id);
        const chevron = row.querySelector('.masjid-chevron');
        const isHidden = content.classList.contains('hidden');
        content.classList.toggle('hidden', !isHidden);
        chevron.classList.toggle('rotate-90', isHidden);
    }
    function expandAll() {
        document.querySelectorAll('.masjid-content-row').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('.masjid-chevron').forEach(el => el.classList.add('rotate-90'));
    }
    function collapseAll() {
        document.querySelectorAll('.masjid-content-row').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.masjid-chevron').forEach(el => el.classList.remove('rotate-90'));
    }
    function filterMasjid(keyword) {
        const q = keyword.toLowerCase().trim();
        document.querySelectorAll('.masjid-row').forEach(row => {
            const show = !q || (row.getAttribute('data-nama') || '').includes(q);
            row.style.display = show ? '' : 'none';
            const next = row.nextElementSibling;
            if (next && next.classList.contains('masjid-content-row')) {
                next.style.display = show ? '' : 'none';
            }
        });
    }
</script>
@endpush