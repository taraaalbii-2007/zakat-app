@forelse ($lembagas as $lembaga)
    @php
        $transaksiMasjid = $lembaga->transaksiPenerimaan ?? collect();
        $nominalTotal    = $transaksiMasjid->sum('jumlah');
        $pendingCount    = $transaksiMasjid->where('status', 'pending')->count();
    @endphp

    {{-- Baris Lembaga --}}
    <tr class="lembaga-row cursor-pointer hover:bg-amber-50/50 transition-colors"
        data-nama="{{ strtolower($lembaga->nama) }}"
        onclick="toggleLembaga('trx-penerimaan-{{ $lembaga->id }}', this)">  {{-- <-- Ganti toggleMasjid jadi toggleLembaga --}}
        <td class="px-4 py-3">
            <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 lembaga-chevron"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </td>
        <td class="px-6 py-3">
            <div class="text-sm font-semibold text-gray-900">{{ $lembaga->nama }}</div>
            <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat transaksi</div>
        </td>
        <td class="px-6 py-3 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                {{ $transaksiMasjid->count() }} Transaksi
            </span>
        </td>
        <td class="px-6 py-3 text-center hidden md:table-cell">
            <span class="text-sm font-semibold text-green-700">Rp {{ number_format($nominalTotal, 0, ',', '.') }}</span>
        </td>
        <td class="px-6 py-3 text-center hidden lg:table-cell">
            @if($pendingCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    {{ $pendingCount }} Pending
                </span>
            @else
                <span class="text-xs text-gray-400">—</span>
            @endif
        </td>
    </tr>

    {{-- Expandable: Tabel Transaksi dengan JS Pagination --}}
    <tr id="trx-penerimaan-{{ $lembaga->id }}" class="hidden lembaga-content-row">
        <td colspan="5" class="p-0">
            <div class="bg-gradient-to-b from-blue-50/50 to-gray-50 border-y border-blue-200/50 px-6 py-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-primary-500 rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Transaksi Penerimaan — {{ $lembaga->nama }}
                    </h3>
                </div>

                @if ($transaksiMasjid->isEmpty())
                    <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                        Belum ada transaksi penerimaan untuk lembaga ini
                    </div>
                @else
                    @php
                        $trxData = $transaksiMasjid->map(function ($trx) {
                            return [
                                'muzakki_nama'    => $trx->muzakki_nama ?? '-',
                                'no_transaksi'    => $trx->no_transaksi ?? '-',
                                'tanggal'         => optional($trx->tanggal_transaksi)->format('d/m/Y') ?? '-',
                                'waktu'           => optional($trx->waktu_transaksi)->format('H:i') ?? '',
                                'jenis_zakat'     => $trx->jenisZakat->nama ?? '-',
                                'jumlah'          => (float) ($trx->jumlah ?? 0),
                                'status'          => $trx->status ?? '-',
                            ];
                        });
                    @endphp

                    <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                 <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Muzakki</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Tanggal</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Jenis Zakat</th>
                                    <th class="px-4 py-2.5 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                    <th class="px-4 py-2.5 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody id="trx-tbody-{{ $lembaga->id }}"
                                   class="bg-white divide-y divide-gray-100">
                            </tbody>
                        </table>

                        <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                            <span id="trx-info-{{ $lembaga->id }}" class="text-xs text-gray-500"></span>
                            <div class="flex items-center gap-1" id="trx-pagination-{{ $lembaga->id }}"></div>
                        </div>
                    </div>

                    <script>
                        if (typeof window.trxData === 'undefined') window.trxData = {};
                        window.trxData[{{ $lembaga->id }}] = @json($trxData);
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