{{-- resources/views/amil/setor-kas/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Setoran Kas')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Detail Setoran Kas</h2>
                    <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $setorKas->no_setor }}</p>
                </div>
                <div class="flex items-center gap-2">
                    {!! $setorKas->status_badge !!}
                    @if($setorKas->bisa_diedit)
                        <a href="{{ route('amil.setor-kas.edit', $setorKas->uuid) }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary hover:bg-primary/5 border border-primary/30 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-6">

            {{-- TIMELINE --}}
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4">Status & Timeline</h3>
                <div class="flex flex-col sm:flex-row gap-4">
                    @foreach($timeline as $i => $step)
                    <div class="flex items-start gap-3 flex-1">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                            {{ $step['active'] ? ($step['color'] === 'green' ? 'bg-green-100 text-green-600' : ($step['color'] === 'red' ? 'bg-red-100 text-red-600' : ($step['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600'))) : 'bg-gray-100 text-gray-400' }}">
                            @if($step['icon'] === 'check')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($step['icon'] === 'x')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @elseif($step['icon'] === 'clock')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium {{ $step['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                            @if($step['date'])
                                <p class="text-xs text-gray-400">{{ $step['date'] }}</p>
                            @endif
                        </div>
                    </div>
                    @if(!$loop->last)
                        <div class="hidden sm:flex items-center text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    @endif
                    @endforeach
                </div>

                {{-- Alasan Penolakan --}}
                @if($setorKas->status === 'ditolak' && $setorKas->alasan_penolakan)
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-xs font-semibold text-red-800 mb-1">Alasan Penolakan:</p>
                    <p class="text-sm text-red-700">{{ $setorKas->alasan_penolakan }}</p>
                </div>
                @endif
            </div>

            {{-- INFO PERIODE & JUMLAH --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Informasi Setoran</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal Setor</p>
                                <p class="text-sm font-medium text-gray-900">{{ $setorKas->tanggal_setor->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Periode</p>
                                <p class="text-sm font-medium text-gray-900">{{ $setorKas->periode_formatted }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Amil Penyetor</p>
                                <p class="text-sm font-medium text-gray-900">{{ $setorKas->amil->nama_lengkap ?? $setorKas->amil->pengguna->username ?? '-' }}</p>
                            </div>
                        </div>
                        @if($setorKas->penerimaSetoran)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Diterima Oleh</p>
                                <p class="text-sm font-medium text-gray-900">{{ $setorKas->penerimaSetoran->username }}</p>
                                @if($setorKas->diterima_at)
                                    <p class="text-xs text-gray-400">{{ $setorKas->diterima_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        @if($setorKas->keterangan)
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Keterangan</p>
                                <p class="text-sm text-gray-700">{{ $setorKas->keterangan }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Breakdown Jumlah --}}
                <div class="space-y-4">
                    <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Rincian Jumlah</h3>
                    <div class="space-y-3">
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                            <p class="text-xs text-blue-600 font-medium mb-1">Jumlah Disetor</p>
                            <p class="text-2xl font-bold text-blue-800">{{ $setorKas->jumlah_disetor_formatted }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <p class="text-xs text-gray-500">Datang Langsung</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $setorKas->jumlah_dari_datang_langsung_formatted }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <p class="text-xs text-gray-500">Dijemput</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $setorKas->jumlah_dari_dijemput_formatted }}</p>
                            </div>
                        </div>
                        @if(!is_null($setorKas->jumlah_dihitung_fisik))
                        <div class="bg-{{ $setorKas->selisih_jumlah >= 0 ? 'green' : 'red' }}-50 border border-{{ $setorKas->selisih_jumlah >= 0 ? 'green' : 'red' }}-200 rounded-xl p-3">
                            <p class="text-xs text-gray-600">Jumlah Dihitung Fisik</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $setorKas->jumlah_dihitung_fisik_formatted }}</p>
                            @if($setorKas->selisih_jumlah != 0)
                            <p class="text-xs mt-1 {{ $setorKas->selisih_jumlah >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                Selisih: {{ $setorKas->selisih_jumlah >= 0 ? '+' : '' }}Rp {{ number_format(abs($setorKas->selisih_jumlah), 0, ',', '.') }}
                            </p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BUKTI FOTO & TANDA TANGAN --}}
            @if($setorKas->bukti_foto_url || $setorKas->tanda_tangan_amil_url || $setorKas->tanda_tangan_penerima_url)
            <div>
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4">Bukti & Tanda Tangan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @if($setorKas->bukti_foto_url)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Foto Bukti Setor</p>
                        <a href="{{ $setorKas->bukti_foto_url }}" target="_blank" class="block">
                            <img src="{{ $setorKas->bukti_foto_url }}" alt="Bukti"
                                class="w-full h-40 object-cover rounded-xl border border-gray-200 hover:border-primary transition-colors">
                        </a>
                    </div>
                    @endif
                    @if($setorKas->tanda_tangan_amil_url)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Tanda Tangan Amil</p>
                        <div class="h-40 rounded-xl border border-gray-200 bg-white flex items-center justify-center p-2">
                            <img src="{{ $setorKas->tanda_tangan_amil_url }}" alt="TTD Amil" class="max-h-full object-contain">
                        </div>
                    </div>
                    @endif
                    @if($setorKas->tanda_tangan_penerima_url)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Tanda Tangan Penerima</p>
                        <div class="h-40 rounded-xl border border-gray-200 bg-white flex items-center justify-center p-2">
                            <img src="{{ $setorKas->tanda_tangan_penerima_url }}" alt="TTD Penerima" class="max-h-full object-contain">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-4 sm:px-6 py-3 bg-gray-50 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('amil.setor-kas.index') }}"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            @if($setorKas->bisa_dihapus)
            <button type="button" onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg text-red-600 bg-white border border-red-300 hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus
            </button>
            @endif
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-sm shadow-xl">
        <div class="flex justify-center mb-4">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-900 text-center mb-1">Hapus Setoran</h3>
        <p class="text-sm text-gray-500 text-center mb-5">Hapus setoran <strong>{{ $setorKas->no_setor }}</strong>? Tidak dapat dibatalkan.</p>
        <div class="flex justify-center gap-3">
            <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                class="w-28 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">Batal</button>
            <form action="{{ route('amil.setor-kas.destroy', $setorKas->uuid) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="w-28 rounded-lg px-4 py-2 bg-red-600 text-sm font-medium text-white hover:bg-red-700 transition-colors">Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection