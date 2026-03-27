@foreach ($amils as $amil)
    @php
        $colors  = ['bg-blue-500','bg-green-500','bg-yellow-500','bg-red-500',
                    'bg-purple-500','bg-pink-500','bg-indigo-500','bg-orange-500'];
        $initial = strtoupper(substr($amil->nama_lengkap, 0, 1));
        $bgColor = $colors[$initial ? (ord($initial) - 65) % count($colors) : 0];
    @endphp

    {{-- Main Row --}}
    <tr class="hover:bg-gray-50 transition-colors cursor-pointer expandable-row"
        data-target="detail-{{ $amil->uuid }}">

        {{-- Expand button --}}
        <td class="px-4 py-4">
            <button type="button"
                class="expand-btn p-1 rounded-lg hover:bg-gray-100 transition-all">
                <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 expand-icon"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </td>

        {{-- Amil --}}
        <td class="px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                    @if ($amil->foto)
                        <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-100"
                            src="{{ Storage::url($amil->foto) }}"
                            alt="{{ $amil->nama_lengkap }}">
                    @else
                        <div class="h-10 w-10 rounded-full {{ $bgColor }} flex items-center justify-center shadow-sm">
                            <span class="text-sm font-medium text-white">{{ $initial }}</span>
                        </div>
                    @endif
                </div>
                <div class="ml-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-900">{{ $amil->nama_lengkap }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $amil->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $amil->jenis_kelamin === 'L' ? 'L' : 'P' }}
                        </span>
                    </div>
                    <div class="text-xs text-gray-500 mt-0.5">
                        {{ $amil->kode_amil }}
                        @if ($amil->wilayah_tugas)
                            &bull; {{ $amil->wilayah_tugas }}
                        @endif
                    </div>
                </div>
            </div>
        </td>

        {{-- Kontak --}}
        <td class="px-6 py-4">
            <div class="space-y-1">
                <div class="flex items-center text-sm text-gray-900">
                    <svg class="w-4 h-4 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $amil->telepon }}
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 text-gray-400 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ Str::limit($amil->email, 28) }}
                </div>
            </div>
        </td>

        {{-- Status --}}
        <td class="px-6 py-4">
            @if ($amil->status === 'aktif')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>Aktif
                </span>
            @elseif ($amil->status === 'cuti')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span>Cuti
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span>Nonaktif
                </span>
            @endif
        </td>

        {{-- Aksi --}}
        <td class="px-6 py-4 text-center">
            <button type="button"
                class="dropdown-toggle inline-flex items-center p-2 text-gray-400
                       hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"
                data-uuid="{{ $amil->uuid }}"
                data-nama="{{ $amil->nama_lengkap }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                </svg>
            </button>
        </td>
    </tr>

    {{-- Expandable Row --}}
    <tr id="detail-{{ $amil->uuid }}" class="hidden expandable-content">
        <td colspan="5" class="px-0 py-0">
            <div class="bg-gray-50 border-y border-gray-100">
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        {{-- Kolom 1 --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Data Pribadi</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Kode Amil</p>
                                    <p class="font-medium text-gray-900">{{ $amil->kode_amil }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Jenis Kelamin</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $amil->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $amil->tempat_lahir }},
                                        {{ $amil->tanggal_lahir ? \Carbon\Carbon::parse($amil->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Kolom 2 --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Kontak & Alamat</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $amil->telepon }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Email</p>
                                    <p class="font-medium text-gray-900 break-all">{{ $amil->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Alamat</p>
                                    <p class="font-medium text-gray-900">{{ $amil->alamat }}</p>
                                </div>
                                @if ($amil->wilayah_tugas)
                                    <div>
                                        <p class="text-xs text-gray-500">Wilayah Tugas</p>
                                        <p class="font-medium text-gray-900">{{ $amil->wilayah_tugas }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Kolom 3 --}}
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Status & Tugas</h4>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Status</p>
                                    @if ($amil->status === 'aktif')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">Aktif</span>
                                    @elseif ($amil->status === 'cuti')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mt-1">Cuti</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">Nonaktif</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tgl. Mulai Tugas</p>
                                    <p class="font-medium text-gray-900">
                                        {{ $amil->tanggal_mulai_tugas ? \Carbon\Carbon::parse($amil->tanggal_mulai_tugas)->format('d/m/Y') : '-' }}
                                    </p>
                                </div>
                                @if ($amil->tanggal_selesai_tugas)
                                    <div>
                                        <p class="text-xs text-gray-500">Tgl. Selesai Tugas</p>
                                        <p class="font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($amil->tanggal_selesai_tugas)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                @endif
                                <div class="pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-400">Bergabung: {{ $amil->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Tombol Aksi Expandable --}}
                    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-end gap-2">
                        <a href="{{ route('amil.show', $amil->uuid) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-primary hover:bg-primary-600
                                   text-white text-xs font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Detail
                        </a>
                        <a href="{{ route('amil.edit', $amil->uuid) }}"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200
                                   text-gray-700 text-xs font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@endforeach