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
                    <div class="text-xs text-gray-400 mt-0.5">Klik untuk lihat amil</div>
                </div>
            </div>
        </td>
        <td class="px-6 py-3 hidden md:table-cell">
            <div class="text-sm text-gray-600">{{ Str::limit($lembaga->alamat ?? '-', 50) }}</div>
        </td>
        <td class="px-6 py-3 text-center">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-800">
                {{ $lembaga->amils->count() }} Amil
            </span>
        </td>
    </tr>

    {{-- Expandable Row: Tabel Amil --}}
    <tr id="lembaga-{{ $lembaga->id }}" class="hidden lembaga-content-row">
        <td colspan="4" class="p-0">
            <div class="bg-gradient-to-b from-primary/5 to-gray-50 border-y border-primary/20 px-6 py-4">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1 h-5 bg-primary rounded-full"></div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Daftar Amil — {{ $lembaga->nama }}
                    </h3>
                </div>

                @if ($lembaga->amils->isEmpty())
                    <div class="text-center py-6 text-sm text-gray-400 bg-white rounded-xl border border-gray-100">
                        Belum ada data amil untuk lembaga ini
                    </div>
                @else
                    @php
                        $amilData = $lembaga->amils->map(function ($amil) {
                            $hasFoto = $amil->foto && Storage::disk('public')->exists($amil->foto);
                            $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
                            $avatarColors = [
                                'ABCD' => 'bg-primary-500',
                                'EFGH' => 'bg-green-500',
                                'IJKL' => 'bg-purple-500',
                                'MNOP' => 'bg-orange-500',
                                'QRST' => 'bg-red-500',
                                'UVWX' => 'bg-teal-500',
                            ];
                            $avatarBg = 'bg-gray-500';
                            foreach ($avatarColors as $letters => $color) {
                                if (in_array($initial, str_split($letters))) {
                                    $avatarBg = $color;
                                    break;
                                }
                            }
                            return [
                                'nama'          => $amil->nama_lengkap,
                                'kode'          => $amil->kode_amil,
                                'jenis_kelamin' => $amil->jenis_kelamin,
                                'telepon'       => $amil->telepon ?? '-',
                                'email'         => $amil->email ?? '-',
                                'status'        => $amil->status,
                                'foto_url'      => $hasFoto ? Storage::url($amil->foto) : null,
                                'initial'       => $initial,
                                'avatar_bg'     => $avatarBg,
                            ];
                        });
                    @endphp

                    <div class="rounded-xl border border-gray-200 overflow-hidden shadow-sm"
                         data-amil-table="{{ $lembaga->id }}">

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Kontak</th>
                                    <th class="px-4 py-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody id="amil-tbody-{{ $lembaga->id }}" class="bg-white divide-y divide-gray-100">
                            </tbody>
                        </table>

                        <div class="bg-white border-t border-gray-100 px-4 py-2.5 flex items-center justify-between gap-3">
                            <span id="amil-info-{{ $lembaga->id }}"
                                  class="text-xs text-gray-500"></span>
                            <div class="flex items-center gap-1"
                                 id="amil-pagination-{{ $lembaga->id }}">
                            </div>
                        </div>
                    </div>

                    <script>
                        if (typeof window.amilData === 'undefined') {
                            window.amilData = {};
                        }
                        window.amilData[{{ $lembaga->id }}] = @json($amilData);
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