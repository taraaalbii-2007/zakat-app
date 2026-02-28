{{-- resources/views/amil/kunjungan/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Jadwal Kunjungan')

@push('styles')
<style>
    /* ── TUJUAN CARDS (sama dengan create) ── */
    .tujuan-card {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: .75rem .5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: border-color .15s, background .15s, transform .15s, box-shadow .15s;
        user-select: none;
    }

    .tujuan-card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 12px -2px rgba(0,0,0,.10);
    }

    .tujuan-card[data-val="verifikasi"]:hover,
    .tujuan-card[data-val="verifikasi"].is-selected {
        border-color: #6366f1;
        background: #eef2ff;
    }

    .tujuan-card[data-val="penyaluran"]:hover,
    .tujuan-card[data-val="penyaluran"].is-selected {
        border-color: #22c55e;
        background: #f0fdf4;
    }

    .tujuan-card[data-val="monitoring"]:hover,
    .tujuan-card[data-val="monitoring"].is-selected {
        border-color: #eab308;
        background: #fefce8;
    }

    .tujuan-card[data-val="lainnya"]:hover,
    .tujuan-card[data-val="lainnya"].is-selected {
        border-color: #9ca3af;
        background: #f9fafb;
    }

    .tujuan-card.is-selected {
        transform: scale(1.03);
        box-shadow: 0 4px 12px -2px rgba(0,0,0,.10);
    }

    .tujuan-check {
        display: none;
        position: absolute;
        top: 5px; right: 5px;
        width: 16px; height: 16px;
        border-radius: 50%;
        background: #22c55e;
        align-items: center;
        justify-content: center;
    }

    .tujuan-card.is-selected .tujuan-check { display: flex; }

    .tujuan-icon { transition: transform .15s; }
    .tujuan-card.is-selected .tujuan-icon,
    .tujuan-card:hover .tujuan-icon { transform: scale(1.1); }
    
    /* Status badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-direncanakan { background: #fef3c7; color: #92400e; }
    .status-selesai { background: #d1fae5; color: #065f46; }
    .status-dibatalkan { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-6">
    {{-- Card Utama untuk Form Update --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900">Edit Jadwal Kunjungan</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Perbarui detail rencana kunjungan</p>
            </div>
            <span class="status-badge status-{{ $kunjungan->status }}">
                {{ $kunjungan->status_label }}
            </span>
        </div>

        {{-- FORM UPDATE --}}
        <form action="{{ route('amil.kunjungan.update', $kunjungan->uuid) }}" method="POST" class="px-4 sm:px-6 pb-4 sm:pb-6 pt-3 space-y-5">
            @csrf
            @method('PUT')

            {{-- SECTION 1: Mustahik --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">1</span>
                    Pilih Mustahik
                </h3>

                <div class="space-y-3 mt-2">
                    <div>
                        <label for="mustahik_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Mustahik <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="mustahik_id" id="mustahik_id"
                                onchange="onMustahikChange(this)"
                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none pr-10 @error('mustahik_id') border-red-500 @enderror">
                                <option value="">-- Pilih Mustahik --</option>
                                @foreach($mustahiks as $m)
                                    <option value="{{ $m->id }}"
                                        data-nama="{{ $m->nama_lengkap }}"
                                        data-noreg="{{ $m->no_registrasi ?? '' }}"
                                        data-alamat="{{ $m->alamat ?? '' }}"
                                        data-telepon="{{ $m->telepon ?? '' }}"
                                        {{ old('mustahik_id', $kunjungan->mustahik_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_lengkap }}{{ $m->no_registrasi ? ' · ' . $m->no_registrasi : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        @error('mustahik_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="mustahik-info" class="{{ old('mustahik_id', $kunjungan->mustahik_id) ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-blue-900" id="info-nama">{{ $kunjungan->mustahik->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-blue-700 mt-0.5" id="info-no-reg">No. Reg: {{ $kunjungan->mustahik->no_registrasi ?? '-' }}</p>
                                <p class="text-xs text-blue-600 mt-1 flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span id="info-alamat-text">{{ $kunjungan->mustahik->alamat ?? '-' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Jadwal --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">2</span>
                    Jadwal & Tujuan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan"
                            value="{{ old('tanggal_kunjungan', $kunjungan->tanggal_kunjungan->format('Y-m-d')) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_kunjungan') border-red-500 @enderror">
                        @error('tanggal_kunjungan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai"
                            value="{{ old('waktu_mulai', $kunjungan->waktu_mulai) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_mulai') border-red-500 @enderror">
                        @error('waktu_mulai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Selesai (Est.)</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai"
                            value="{{ old('waktu_selesai', $kunjungan->waktu_selesai) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tujuan --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tujuan Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" id="tujuan-grid">
                            @php
                                $tujuanList = [
                                    'verifikasi' => ['label' => 'Verifikasi',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',   'color' => '#6366f1'],
                                    'penyaluran' => ['label' => 'Penyaluran', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#22c55e'],
                                    'monitoring'  => ['label' => 'Monitoring',  'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'color' => '#eab308'],
                                    'lainnya'     => ['label' => 'Lainnya',     'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => '#9ca3af'],
                                ];
                            @endphp
                            @foreach($tujuanList as $val => $opt)
                            <label class="tujuan-card {{ old('tujuan', $kunjungan->tujuan) === $val ? 'is-selected' : '' }}" data-val="{{ $val }}">
                                <input type="radio" name="tujuan" value="{{ $val }}" class="sr-only"
                                    {{ old('tujuan', $kunjungan->tujuan) === $val ? 'checked' : '' }}>
                                <span class="tujuan-check">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <svg class="tujuan-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    style="color:{{ $opt['color'] }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $opt['icon'] }}"/>
                                </svg>
                                <span class="text-xs font-medium text-gray-700">{{ $opt['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @error('tujuan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Catatan --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">3</span>
                    Catatan Rencana
                </h3>
                <textarea name="catatan" id="catatan" rows="3"
                    placeholder="Tuliskan catatan atau hal yang perlu dipersiapkan untuk kunjungan ini..."
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('catatan') border-red-500 @enderror">{{ old('catatan', $kunjungan->catatan) }}</textarea>
                @error('catatan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('amil.kunjungan.show', $kunjungan->uuid) }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Update Jadwal
                </button>
            </div>
        </form>
    </div>

    {{-- CARD TERPISAH UNTUK CANCEL BUTTON --}}
    {{-- DILETAKKAN DI LUAR FORM UPDATE AGAR TIDAK TERSUBMIT BERSAMA FORM UTAMA --}}
    @if($kunjungan->status === 'direncanakan')
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900">Batalkan Kunjungan</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Jika kunjungan ini batal, status akan berubah menjadi dibatalkan</p>
                </div>
                <button type="button" onclick="confirmCancel()"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-red-300 text-sm font-medium rounded-xl text-red-700 bg-white hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan Kunjungan
                </button>
            </div>
        </div>
    </div>

    {{-- FORM CANCEL TERPISAH (HIDDEN) --}}
    <form id="cancel-form" action="{{ route('amil.kunjungan.cancel', $kunjungan->uuid) }}" method="POST" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    @endif
</div>
@endsection

@push('scripts')
<script>
// ── Tujuan card selection ────────────────────────────────────────────────────
document.querySelectorAll('.tujuan-card').forEach(card => {
    card.addEventListener('click', function () {
        document.querySelectorAll('.tujuan-card').forEach(c => c.classList.remove('is-selected'));
        this.classList.add('is-selected');
        const radio = this.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    });
});

// ── Mustahik info box ────────────────────────────────────────────────────────
function onMustahikChange(select) {
    const option  = select.options[select.selectedIndex];
    const infoBox = document.getElementById('mustahik-info');

    if (!select.value) {
        infoBox.classList.add('hidden');
        return;
    }

    document.getElementById('info-nama').textContent       = option.dataset.nama   || '-';
    document.getElementById('info-no-reg').textContent     = 'No. Reg: ' + (option.dataset.noreg || '-');
    document.getElementById('info-alamat-text').textContent = option.dataset.alamat || '-';
    infoBox.classList.remove('hidden');
}

// ── Konfirmasi pembatalan ────────────────────────────────────────────────────
function confirmCancel() {
    if (confirm('Apakah Anda yakin ingin membatalkan kunjungan ini?')) {
        document.getElementById('cancel-form').submit();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('mustahik_id');
    if (select && select.value) onMustahikChange(select);
});
</script>
@endpush