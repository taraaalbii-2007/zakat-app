@extends('layouts.app')

@section('title', 'Data Amil Semua Masjid')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
            <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900">Data Amil Semua Masjid</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            Total: {{ $totalAmil }} Amil dari {{ $masjids->count() }} Masjid
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
                            <input type="search" id="cari-masjid" placeholder="Cari nama masjid..."
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
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Amil</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tbody-masjid">
                        @forelse ($masjids as $masjid)
                            <tr class="masjid-row cursor-pointer hover:bg-primary/5 transition-colors"
                                data-nama="{{ strtolower($masjid->nama) }}"
                                onclick="toggleMasjid('masjid-{{ $masjid->id }}', this)">
                                <td class="px-4 py-3">
                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 masjid-chevron"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $masjid->nama }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat amil</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 hidden md:table-cell">
                                    <div class="text-sm text-gray-600">{{ Str::limit($masjid->alamat ?? '-', 50) }}</div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ $masjid->amils->count() }} Amil
                                    </span>
                                </td>
                            </tr>

                            {{-- Expandable Row: Tabel Amil --}}
                            <tr id="masjid-{{ $masjid->id }}" class="hidden masjid-content-row">
                                <td colspan="4" class="p-0">
                                    <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="w-1 h-5 bg-primary rounded-full"></div>
                                            <h3 class="text-sm font-semibold text-gray-800">
                                                Daftar Amil — {{ $masjid->nama }}
                                            </h3>
                                        </div>

                                        @if ($masjid->amils->isEmpty())
                                            <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                                                Belum ada data amil untuk masjid ini
                                            </div>
                                        @else
                                            <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-white">
                                                        <tr>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kontak</th>
                                                            <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-100">
                                                        @foreach ($masjid->amils as $amil)
                                                            <tr class="hover:bg-gray-50 transition-colors">
                                                                <td class="px-4 py-3">
                                                                    <div class="flex items-center gap-3">
                                                                        <img class="h-8 w-8 rounded-full object-cover ring-2 ring-gray-100 flex-shrink-0"
                                                                             src="{{ $amil->foto_url }}"
                                                                             alt="{{ $amil->nama_lengkap }}">
                                                                        <div>
                                                                            <div class="flex items-center gap-1.5">
                                                                                <span class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</span>
                                                                                @if($amil->jenis_kelamin == 'L')
                                                                                    <span class="px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">L</span>
                                                                                @else
                                                                                    <span class="px-1.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-700">P</span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="text-xs text-gray-400">{{ $amil->kode_amil }}</div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 hidden sm:table-cell">
                                                                    <div class="text-sm text-gray-700">{{ $amil->telepon }}</div>
                                                                    <div class="text-xs text-gray-400">{{ Str::limit($amil->email, 25) }}</div>
                                                                </td>
                                                                <td class="px-4 py-3">
                                                                    @if($amil->status == 'aktif')
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1"></span>Aktif
                                                                        </span>
                                                                    @elseif($amil->status == 'cuti')
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1"></span>Cuti
                                                                        </span>
                                                                    @else
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1"></span>Nonaktif
                                                                        </span>
                                                                    @endif
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
            const nama = (row.getAttribute('data-nama') || '');
            const show = !q || nama.includes(q);
            row.style.display = show ? '' : 'none';
            const next = row.nextElementSibling;
            if (next && next.classList.contains('masjid-content-row')) {
                if (!show) next.style.display = 'none';
                else next.style.display = '';
            }
        });
    }
</script>
@endpush