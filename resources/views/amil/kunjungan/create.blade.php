{{-- resources/views/amil/kunjungan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Jadwal Kunjungan')

@push('styles')
<style>
    /* ── CUSTOM SEARCHABLE DROPDOWN ─────────────────────────── */
    .custom-select-wrap { position: relative; width: 100%; }

    /* Tombol trigger */
    .custom-select-trigger {
        display: flex; align-items: center; justify-content: space-between;
        width: 100%; padding: 8px 12px; font-size: 0.875rem;
        border: 1px solid #d1d5db; border-radius: 0.75rem;
        background: #fff; cursor: pointer; user-select: none;
        transition: border-color .15s, box-shadow .15s;
        color: #374151;
    }
    .custom-select-trigger.placeholder-shown { color: #9ca3af; }
    .custom-select-trigger:hover { border-color: #9ca3af; }
    .custom-select-trigger.open,
    .custom-select-trigger:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        outline: none;
    }
    .custom-select-trigger .caret {
        width: 14px; height: 14px; color: #9ca3af; flex-shrink: 0; margin-left: 8px;
        transition: transform .2s;
    }
    .custom-select-trigger.open .caret { transform: rotate(180deg); }

    /* Dropdown panel */
    .custom-select-dropdown {
        position: absolute; top: calc(100% + 4px); left: 0; right: 0;
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 0.75rem; box-shadow: 0 10px 28px -4px rgba(0,0,0,.14);
        z-index: 9999; display: none; overflow: hidden;
    }
    .custom-select-dropdown.open { display: block; }

    /* Search input di dalam dropdown */
    .custom-select-search-wrap {
        padding: 8px 10px; border-bottom: 1px solid #f3f4f6;
        background: #fafafa; position: sticky; top: 0;
    }
    .custom-select-search {
        width: 100%; padding: 7px 10px 7px 32px; font-size: 0.8rem;
        border: 1px solid #d1d5db; border-radius: 8px;
        outline: none; background: #fff; color: #374151;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24' width='14' height='14'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='M21 21l-4.35-4.35'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: 9px center;
        background-size: 14px; box-sizing: border-box;
        transition: border-color .15s, box-shadow .15s;
    }
    .custom-select-search:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 2px rgba(22,163,74,.1);
    }

    /* List opsi */
    .custom-select-list { max-height: 240px; overflow-y: auto; }
    .custom-select-option {
        display: flex; align-items: center; justify-content: space-between;
        padding: 9px 14px; font-size: 0.875rem; color: #374151;
        cursor: pointer; gap: 8px;
    }
    .custom-select-option:hover { background: #f0fdf4; color: #15803d; }
    .custom-select-option.selected { background: #dcfce7; color: #15803d; font-weight: 500; }
    .custom-select-option.hidden { display: none; }
    .custom-select-option .opt-nama { font-weight: 500; }
    .custom-select-option .opt-noreg { font-size: 0.72rem; color: #9ca3af; white-space: nowrap; }
    .custom-select-option.selected .opt-noreg { color: #86efac; }
    .custom-select-empty {
        padding: 12px 14px; font-size: 0.8rem; color: #9ca3af; text-align: center;
    }

    /* ── TUJUAN CARDS ───────────────────────────────────────── */
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
    .tujuan-card:hover { transform: scale(1.03); box-shadow: 0 4px 12px -2px rgba(0,0,0,.10); }

    .tujuan-card[data-val="verifikasi"]:hover,
    .tujuan-card[data-val="verifikasi"].is-selected { border-color: #6366f1; background: #eef2ff; }
    .tujuan-card[data-val="penyaluran"]:hover,
    .tujuan-card[data-val="penyaluran"].is-selected { border-color: #22c55e; background: #f0fdf4; }
    .tujuan-card[data-val="monitoring"]:hover,
    .tujuan-card[data-val="monitoring"].is-selected { border-color: #eab308; background: #fefce8; }
    .tujuan-card[data-val="lainnya"]:hover,
    .tujuan-card[data-val="lainnya"].is-selected    { border-color: #9ca3af; background: #f9fafb; }
    .tujuan-card.is-selected { transform: scale(1.03); box-shadow: 0 4px 12px -2px rgba(0,0,0,.10); }

    .tujuan-check {
        display: none; position: absolute; top: 5px; right: 5px;
        width: 16px; height: 16px; border-radius: 50%;
        background: #22c55e; align-items: center; justify-content: center;
    }
    .tujuan-card.is-selected .tujuan-check { display: flex; }
    .tujuan-icon { transition: transform .15s; }
    .tujuan-card.is-selected .tujuan-icon,
    .tujuan-card:hover .tujuan-icon { transform: scale(1.1); }

    /* ── Slide-up ────────────────────────────────────────────── */
    @keyframes slideUp { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
    .animate-slide-up { animation: slideUp .2s ease both; }
</style>
@endpush

@section('content')
<div class="space-y-4">

    {{-- Flash error --}}
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl animate-slide-up">
        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="flex-1 text-sm font-medium text-red-800">{{ session('error') }}</p>
    </div>
    @endif

    {{-- MAIN CARD --}}
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 overflow-hidden animate-slide-up">

        {{-- ── Header: rapat ── --}}
        <div class="px-5 sm:px-6 py-3 border-b border-gray-100">
            <h2 class="text-base sm:text-lg font-bold text-gray-900">Buat Jadwal Kunjungan</h2>
            <p class="text-xs text-gray-500 mt-0.5">Isi detail rencana kunjungan mustahik</p>
        </div>

        {{-- ── Form: py dikecilkan jadi py-3 bukan py-4 ── --}}
        <form action="{{ route('amil.kunjungan.store') }}" method="POST"
            class="px-5 sm:px-6 pt-3 pb-5 space-y-4">
            @csrf

            {{-- ═══════ SECTION 1: Mustahik ═══════ --}}
            <div>
                {{-- Section title — margin bawah kecil --}}
                <h3 class="text-sm font-semibold text-gray-900 mb-2 pb-1.5 border-b border-gray-100 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">1</span>
                    Pilih Mustahik
                </h3>

                <div class="space-y-2.5">
                    <div>
                        <label for="mustahik_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Mustahik <span class="text-red-500">*</span>
                        </label>

                        {{-- Hidden input untuk nilai yang dikirim ke server --}}
                        <input type="hidden" name="mustahik_id" id="mustahik_id" value="{{ old('mustahik_id') }}">

                        {{-- Custom searchable dropdown --}}
                        <div class="custom-select-wrap" id="mustahik-select-wrap">

                            {{-- Trigger (tombol buka/tutup) --}}
                            <div class="custom-select-trigger placeholder-shown" id="mustahik-trigger"
                                tabindex="0" role="combobox" aria-expanded="false">
                                <span id="mustahik-trigger-text">-- Pilih Mustahik --</span>
                                <svg class="caret" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>

                            {{-- Dropdown panel --}}
                            <div class="custom-select-dropdown" id="mustahik-dropdown">
                                {{-- Search --}}
                                <div class="custom-select-search-wrap">
                                    <input type="text" class="custom-select-search"
                                        id="mustahik-search"
                                        placeholder="Cari nama mustahik..."
                                        autocomplete="off">
                                </div>
                                {{-- List --}}
                                <div class="custom-select-list" id="mustahik-list">
                                    <div class="custom-select-option" data-value="" data-text="-- Pilih Mustahik --">
                                        <span class="opt-nama" style="color:#9ca3af;font-style:italic">-- Pilih Mustahik --</span>
                                    </div>
                                    @foreach($mustahiks as $m)
                                    <div class="custom-select-option {{ old('mustahik_id') == $m->id ? 'selected' : '' }}"
                                        data-value="{{ $m->id }}"
                                        data-text="{{ $m->nama_lengkap }}{{ $m->no_registrasi ? ' · '.$m->no_registrasi : '' }}"
                                        data-nama="{{ $m->nama_lengkap }}"
                                        data-noreg="{{ $m->no_registrasi ?? '' }}"
                                        data-alamat="{{ $m->alamat ?? '' }}"
                                        data-telepon="{{ $m->telepon ?? '' }}">
                                        <span class="opt-nama">{{ $m->nama_lengkap }}</span>
                                        @if($m->no_registrasi)
                                            <span class="opt-noreg">{{ $m->no_registrasi }}</span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <div class="custom-select-empty hidden" id="mustahik-empty">Mustahik tidak ditemukan</div>
                            </div>
                        </div>
                        @error('mustahik_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info box mustahik yang dipilih --}}
                    <div id="mustahik-info" class="{{ old('mustahik_id') ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-blue-900" id="info-nama">-</p>
                                <p class="text-xs text-blue-700 mt-0.5" id="info-no-reg"></p>
                                <p class="text-xs text-blue-600 mt-1 flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span id="info-alamat-text">-</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══════ SECTION 2: Jadwal & Tujuan ═══════ --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2 pb-1.5 border-b border-gray-100 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">2</span>
                    Jadwal &amp; Tujuan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    {{-- Tanggal --}}
                    <div class="sm:col-span-2">
                        <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan"
                            value="{{ old('tanggal_kunjungan', request('tanggal', date('Y-m-d'))) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_kunjungan') border-red-500 @enderror">
                        @error('tanggal_kunjungan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Waktu Mulai --}}
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai"
                            value="{{ old('waktu_mulai') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_mulai') border-red-500 @enderror">
                        @error('waktu_mulai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Waktu Selesai --}}
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai (Est.)</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai"
                            value="{{ old('waktu_selesai') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tujuan cards --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tujuan Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach([
                                'verifikasi' => ['label'=>'Verifikasi',  'icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',   'color'=>'#6366f1'],
                                'penyaluran' => ['label'=>'Penyaluran', 'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'#22c55e'],
                                'monitoring'  => ['label'=>'Monitoring',  'icon'=>'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'color'=>'#eab308'],
                                'lainnya'     => ['label'=>'Lainnya',     'icon'=>'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color'=>'#9ca3af'],
                            ] as $val => $opt)
                            <label class="tujuan-card {{ old('tujuan')===$val ? 'is-selected' : '' }}" data-val="{{ $val }}">
                                <input type="radio" name="tujuan" value="{{ $val }}" class="sr-only"
                                    {{ old('tujuan')===$val ? 'checked' : '' }}>
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

            {{-- ═══════ SECTION 3: Catatan ═══════ --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2 pb-1.5 border-b border-gray-100 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs font-bold">3</span>
                    Catatan Rencana
                </h3>
                <textarea name="catatan" id="catatan" rows="3"
                    placeholder="Tuliskan catatan atau hal yang perlu dipersiapkan..."
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                @error('catatan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('amil.kunjungan.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" name="langsung_selesai" value="0"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-primary text-sm font-medium rounded-xl text-primary bg-white hover:bg-primary/5 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Simpan Jadwal
                </button>
                <button type="submit" name="langsung_selesai" value="1"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan &amp; Isi Hasil
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Elemen-elemen dropdown ─────────────────────────────────────────────
    const hiddenInput = document.getElementById('mustahik_id');
    const trigger     = document.getElementById('mustahik-trigger');
    const triggerText = document.getElementById('mustahik-trigger-text');
    const dropdown    = document.getElementById('mustahik-dropdown');
    const searchInput = document.getElementById('mustahik-search');
    const list        = document.getElementById('mustahik-list');
    const emptyMsg    = document.getElementById('mustahik-empty');
    const options     = list.querySelectorAll('.custom-select-option');

    // ── Buka / tutup dropdown ──────────────────────────────────────────────
    function openDropdown() {
        dropdown.classList.add('open');
        trigger.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
        searchInput.value = '';
        filterOptions('');
        setTimeout(function () { searchInput.focus(); }, 30);
    }

    function closeDropdown() {
        dropdown.classList.remove('open');
        trigger.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
    }

    // Toggle saat klik trigger
    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.contains('open') ? closeDropdown() : openDropdown();
    });

    // Buka dengan keyboard
    trigger.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openDropdown(); }
        if (e.key === 'Escape') closeDropdown();
    });

    // Tutup saat klik di luar
    document.addEventListener('click', function (e) {
        if (!document.getElementById('mustahik-select-wrap').contains(e.target)) {
            closeDropdown();
        }
    });

    // ── Filter / search ────────────────────────────────────────────────────
    searchInput.addEventListener('input', function () {
        filterOptions(this.value.trim().toLowerCase());
    });

    // Hentikan klik di dalam dropdown agar tidak trigger close
    dropdown.addEventListener('mousedown', function (e) { e.stopPropagation(); });

    function filterOptions(query) {
        let visibleCount = 0;
        options.forEach(function (opt) {
            const text = opt.dataset.text.toLowerCase();
            if (!query || text.includes(query)) {
                opt.classList.remove('hidden');
                visibleCount++;
            } else {
                opt.classList.add('hidden');
            }
        });
        emptyMsg.classList.toggle('hidden', visibleCount > 0);
        list.classList.toggle('hidden', visibleCount === 0);
    }

    // ── Pilih opsi ─────────────────────────────────────────────────────────
    options.forEach(function (opt) {
        opt.addEventListener('click', function () {
            const value = this.dataset.value;
            const text  = this.dataset.text;

            // Update hidden input
            hiddenInput.value = value;

            // Update teks trigger
            if (value) {
                triggerText.textContent = text.split(' · ')[0]; // hanya nama
                trigger.classList.remove('placeholder-shown');
            } else {
                triggerText.textContent = '-- Pilih Mustahik --';
                trigger.classList.add('placeholder-shown');
            }

            // Tandai opsi terpilih
            options.forEach(function (o) { o.classList.remove('selected'); });
            this.classList.add('selected');

            // Tampilkan info box
            tampilInfoMustahik(this);

            closeDropdown();
        });
    });

    // ── Info box mustahik ──────────────────────────────────────────────────
    function tampilInfoMustahik(optEl) {
        const box = document.getElementById('mustahik-info');
        if (!optEl || !optEl.dataset.value) { box.classList.add('hidden'); return; }

        document.getElementById('info-nama').textContent        = optEl.dataset.nama   || '-';
        document.getElementById('info-no-reg').textContent      = optEl.dataset.noreg  ? 'No. Reg: ' + optEl.dataset.noreg : '';
        document.getElementById('info-alamat-text').textContent = optEl.dataset.alamat || '-';
        box.classList.remove('hidden');
    }

    // Inisialisasi: jika ada old() value tampilkan label & info
    const oldVal = hiddenInput.value;
    if (oldVal) {
        const selectedOpt = list.querySelector(`.custom-select-option[data-value="${oldVal}"]`);
        if (selectedOpt) {
            triggerText.textContent = selectedOpt.dataset.nama || selectedOpt.dataset.text;
            trigger.classList.remove('placeholder-shown');
            selectedOpt.classList.add('selected');
            tampilInfoMustahik(selectedOpt);
        }
    }

    // ── Tujuan card selection ──────────────────────────────────────────────
    document.querySelectorAll('.tujuan-card').forEach(function (card) {
        card.addEventListener('click', function () {
            document.querySelectorAll('.tujuan-card').forEach(function (c) {
                c.classList.remove('is-selected');
            });
            this.classList.add('is-selected');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });
    });
});
</script>
@endpush