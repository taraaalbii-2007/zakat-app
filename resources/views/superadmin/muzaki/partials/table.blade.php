@forelse ($lembagas as $lembaga)
    {{-- Baris Lembaga --}}
    <tr class="lembaga-row cursor-pointer hover:bg-primary/5 transition-colors"
        data-nama="{{ strtolower($lembaga->nama) }}"
        onclick="toggleLembaga('lembaga-{{ $lembaga->id }}', this)">
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
                    <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat muzaki</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-3 hidden md:table-cell">
            <div class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</div>
        </td>
        <td class="px-6 py-3 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                {{ $lembaga->muzakkiCount }} Muzaki
            </span>
        </td>
        <td class="px-6 py-3 text-center hidden lg:table-cell">
            <span class="text-sm font-semibold text-gray-700">
                Rp {{ number_format($lembaga->totalNominal ?? 0, 0, ',', '.') }}
            </span>
        </td>
    </tr>

    {{-- Expandable Row: Tabel Muzaki dengan JS Pagination --}}
    <tr id="lembaga-{{ $lembaga->id }}" class="hidden lembaga-content-row">
        <td colspan="5" class="p-0">
            <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-primary rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Daftar Muzaki — {{ $lembaga->nama }}
                    </h3>
                </div>

                @if ($lembaga->muzakkis->isEmpty())
                    <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                        Belum ada data muzaki untuk lembaga ini
                    </div>
                @else
                    @php
                        $muzakiData = $lembaga->muzakkis->map(function ($m) use ($lembaga) {
                            $jenisZakat = collect(
                                array_filter(explode(',', $m->jenis_zakat_list ?? ''))
                            )->unique()->values();
                            return [
                                'nama'              => $m->muzakki_nama,
                                'initial'           => strtoupper(substr($m->muzakki_nama, 0, 1)),
                                'nik'               => $m->muzakki_nik ?? null,
                                'telepon'           => $m->muzakki_telepon ?? null,
                                'email'             => $m->muzakki_email ?? null,
                                'jenis_zakat'       => $jenisZakat->toArray(),
                                'total_transaksi'   => $m->total_transaksi,
                                'total_nominal'     => (int) $m->total_nominal,
                                'transaksi_terakhir'=> $m->transaksi_terakhir
                                    ? \Carbon\Carbon::parse($m->transaksi_terakhir)->translatedFormat('d M Y')
                                    : '-',
                                'detail_url'        => route('muzaki.show', [
                                    'nama'       => $m->muzakki_nama,
                                    'lembaga_id' => $lembaga->id,
                                ]),
                            ];
                        });
                    @endphp

                    <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Muzaki</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Jenis Zakat</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Total Nominal</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Terakhir</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="muzaki-tbody-{{ $lembaga->id }}"
                                   class="bg-white divide-y divide-gray-100">
                            </tbody>
                        </table>

                        <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                            <span id="muzaki-info-{{ $lembaga->id }}" class="text-xs text-gray-500"></span>
                            <div class="flex items-center gap-1" id="muzaki-pagination-{{ $lembaga->id }}"></div>
                        </div>
                    </div>

                    <script>
                        if (typeof window.muzakiData === 'undefined') window.muzakiData = {};
                        window.muzakiData[{{ $lembaga->id }}] = @json($muzakiData);
                    </script>
                @endif
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
            Belum ada data lembaga
        </td>
    </tr>
@endforelse