@forelse ($lembagas as $lembaga)
    {{-- Baris Lembaga --}}
    <tr class="lembaga-row cursor-pointer hover:bg-primary/5 transition-colors"
        data-nama="{{ strtolower($lembaga->nama) }}"
        onclick="toggleLembaga('mustahik-lembaga-{{ $lembaga->id }}', this)">
        <td class="px-4 py-3">
            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </td>
        <td class="px-6 py-3">
            <div class="flex items-center gap-3">
                <div>
                    <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat mustahik</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-3 hidden md:table-cell">
            <div class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</div>
        </td>
        <td class="px-6 py-3 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                {{ $lembaga->mustahiks->count() }} Mustahik
            </span>
        </td>
    </tr>

    {{-- Expandable Row: Tabel Mustahik dengan JS Pagination --}}
    <tr id="mustahik-lembaga-{{ $lembaga->id }}" class="hidden lembaga-content-row">
        <td colspan="4" class="p-0">
            <div class="bg-gradient-to-b from-green-50/50 to-gray-50 border-y border-green-200/50 px-6 py-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-green-500 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Daftar Mustahik — {{ $lembaga->nama }}
                    </h3>
                </div>

                @if ($lembaga->mustahiks->isEmpty())
                    <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                        Belum ada data mustahik untuk lembaga ini
                    </div>
                @else
                    @php
                        $mustahikData = $lembaga->mustahiks->map(function ($m) {
                            return [
                                'no_registrasi'     => $m->no_registrasi ?? '-',
                                'tanggal'           => $m->tanggal_registrasi ? $m->tanggal_registrasi->format('d M Y') : '-',
                                'nama'              => $m->nama_lengkap,
                                'initial'           => strtoupper(substr($m->nama_lengkap, 0, 1)),
                                'nik'               => $m->nik ?? null,
                                'kategori'          => $m->kategoriMustahik->nama ?? null,
                                'status_verifikasi' => $m->status_verifikasi,
                                'is_active'         => (bool) $m->is_active,
                            ];
                        });
                    @endphp

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
                            <tbody id="mustahik-tbody-{{ $lembaga->id }}"
                                   class="bg-white divide-y divide-gray-100">
                            </tbody>
                        </table>

                        <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                            <span id="mustahik-info-{{ $lembaga->id }}"
                                  class="text-xs text-gray-500"></span>
                            <div class="flex items-center gap-1"
                                 id="mustahik-pagination-{{ $lembaga->id }}">
                            </div>
                        </div>
                    </div>

                    <script>
                        if (typeof window.mustahikData === 'undefined') window.mustahikData = {};
                        window.mustahikData[{{ $lembaga->id }}] = @json($mustahikData);
                    </script>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">
            Belum ada data lembaga
        </td>
    </tr>
@endforelse