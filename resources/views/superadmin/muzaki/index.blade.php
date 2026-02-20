@extends('layouts.app')

@section('title', 'Kelola Muzaki')

@section('content')
<div class="p-6 space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Kelola Muzaki</h1>
            <p class="text-sm text-gray-500 mt-0.5">Data muzaki dari seluruh masjid yang terdaftar</p>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total Muzaki Unik --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Total Muzaki Unik</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_muzakki_unik']) }}</p>
            <p class="text-xs text-gray-400 mt-1">lintas semua masjid</p>
        </div>

        {{-- Total Transaksi --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Total Transaksi</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_transaksi']) }}</p>
            <p class="text-xs text-gray-400 mt-1">semua status</p>
        </div>

        {{-- Total Nominal --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 col-span-2 lg:col-span-1">
            <p class="text-xs text-gray-500 mb-1">Total Nominal (Verified)</p>
            <p class="text-lg font-bold text-green-600">Rp {{ number_format($stats['total_nominal'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">semua masjid</p>
        </div>

        {{-- Bulan Ini - Transaksi --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Transaksi Bulan Ini</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['transaksi_bulan_ini']) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>

        {{-- Bulan Ini - Nominal --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Nominal Bulan Ini</p>
            <p class="text-lg font-bold text-orange-600">Rp {{ number_format($stats['nominal_bulan_ini'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <form method="GET" action="{{ route('muzaki.index') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Pencarian --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Muzaki</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Nama, email, telepon, NIK..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>

            {{-- Filter Masjid --}}
            <div class="min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Masjid</label>
                <select name="masjid_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Masjid</option>
                    @foreach($masjidList as $masjid)
                        <option value="{{ $masjid->id }}" {{ request('masjid_id') == $masjid->id ? 'selected' : '' }}>
                            {{ $masjid->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tahun --}}
            <div class="min-w-[120px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Tahun</label>
                <select name="tahun" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div class="min-w-[130px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Urutkan</label>
                <select name="sort_by" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="transaksi_terakhir" {{ request('sort_by') === 'transaksi_terakhir' ? 'selected' : '' }}>Transaksi Terakhir</option>
                    <option value="muzakki_nama"       {{ request('sort_by') === 'muzakki_nama'       ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="total_transaksi"    {{ request('sort_by') === 'total_transaksi'    ? 'selected' : '' }}>Total Transaksi</option>
                    <option value="total_nominal"      {{ request('sort_by') === 'total_nominal'      ? 'selected' : '' }}>Total Nominal</option>
                    <option value="nama_masjid"        {{ request('sort_by') === 'nama_masjid'        ? 'selected' : '' }}>Nama Masjid</option>
                </select>
            </div>

            <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">

            {{-- Tombol --}}
            <div class="flex gap-2">
                <button type="submit"
                        class="px-4 py-2 text-sm bg-[#2d6a2d] text-white rounded-lg hover:bg-[#245424] transition-colors font-medium">
                    Filter
                </button>
                @if(request()->hasAny(['q', 'masjid_id', 'tahun', 'bulan', 'sort_by']))
                    <a href="{{ route('muzaki.index') }}"
                       class="px-4 py-2 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Info hasil --}}
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-semibold text-gray-700">{{ $muzakkiList->firstItem() ?? 0 }}</span>–<span class="font-semibold text-gray-700">{{ $muzakkiList->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-gray-700">{{ number_format($muzakkiList->total()) }}</span> muzaki
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Muzaki</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Masjid</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi</th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Nominal</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaksi Terakhir</th>
                        <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($muzakkiList as $muzakki)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            {{-- Muzaki Info --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-semibold text-green-700">
                                            {{ strtoupper(substr($muzakki->muzakki_nama, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $muzakki->muzakki_nama }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ $muzakki->muzakki_telepon ?? '-' }}
                                            @if($muzakki->muzakki_email)
                                                &bull; {{ $muzakki->muzakki_email }}
                                            @endif
                                        </p>
                                        @if($muzakki->muzakki_nik)
                                            <p class="text-xs text-gray-400">NIK: {{ $muzakki->muzakki_nik }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Masjid --}}
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-gray-700">{{ $muzakki->nama_masjid }}</p>
                                <p class="text-xs text-gray-400">{{ $muzakki->kode_masjid }}</p>
                            </td>

                            {{-- Total Transaksi --}}
                            <td class="px-4 py-3.5 text-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-700 text-sm font-bold">
                                    {{ $muzakki->total_transaksi }}
                                </span>
                            </td>

                            {{-- Total Nominal --}}
                            <td class="px-5 py-3.5 text-right">
                                <p class="font-semibold text-gray-800">
                                    Rp {{ number_format($muzakki->total_nominal, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">verified</p>
                            </td>

                            {{-- Status badge --}}
                            <td class="px-4 py-3.5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    @if($muzakki->total_verified > 0)
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                            {{ $muzakki->total_verified }} verified
                                        </span>
                                    @endif
                                    @if($muzakki->total_pending > 0)
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">
                                            {{ $muzakki->total_pending }} pending
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Transaksi Terakhir --}}
                            <td class="px-4 py-3.5 text-center">
                                <p class="text-sm text-gray-700">
                                    {{ $muzakki->transaksi_terakhir ? \Carbon\Carbon::parse($muzakki->transaksi_terakhir)->translatedFormat('d M Y') : '-' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Pertama: {{ $muzakki->transaksi_pertama ? \Carbon\Carbon::parse($muzakki->transaksi_pertama)->translatedFormat('d M Y') : '-' }}
                                </p>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-3.5 text-center">
                                <a href="{{ route('muzaki.show', ['nama' => $muzakki->muzakki_nama, 'masjid_id' => $muzakki->masjid_id]) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-[#2d6a2d] bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-gray-400 font-medium">Belum ada data muzaki</p>
                                <p class="text-gray-300 text-xs mt-1">Data muzaki berasal dari transaksi penerimaan yang sudah diinput</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($muzakkiList->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $muzakkiList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection