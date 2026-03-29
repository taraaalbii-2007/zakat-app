{{--
    resources/views/amil/transaksi-datang-langsung/create-dijemput.blade.php

    DIPAKAI OLEH  : Amil / Admin Lembaga SAJA
    CONTROLLER    : createDijemput() → return view('...create-dijemput')
    STORE         : POST route('transaksi-datang-langsung.store') dengan metode_penerimaan=dijemput (hidden)

    FLOW          : 1 Step saja — isi data muzakki + lokasi + pilih amil → Submit
                    Controller store() deteksi isDijemput → simpan status=pending,
                    status_penjemputan=menunggu. Detail zakat & pembayaran diisi
                    amil saat tiba via edit/completePickupTransaction().

    TIDAK ADA     : Pilihan metode_penerimaan (sudah fix = dijemput via hidden field)
                    Step 2 (detail zakat) & Step 3 (pembayaran)

    FIELD WAJIB   : muzakki_nama, amil_id, latitude, longitude
    FIELD OPSIONAL: muzakki_nik, muzakki_telepon, muzakki_email, muzakki_alamat, keterangan

    DATA MUZAKKI  : Sudah dipisah dari tabel transaksi.
                    Snapshot disimpan di kolom muzakki_nama, muzakki_telepon, dst.
                    muzakki_id (FK) diisi jika diinput oleh muzakki (bukan kasus ini).
--}}

@extends('layouts.app')

@section('title', 'Tambah Permintaan Penjemputan Zakat')

@section('content')
<div class="space-y-4 sm:space-y-6">

{{-- ══════════════════════════════════════════════════════════════
     HEADER
══════════════════════════════════════════════════════════════ --}}
<div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">

    <div class="px-4 sm:px-6 py-3 bg-gradient-to-r from-green-50 via-emerald-50 to-teal-50 border-b border-green-100">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900">Permintaan Penjemputan Zakat</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                        Dijemput
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ $lembaga->nama }} &middot; No: <span class="font-mono font-semibold text-gray-700">{{ $noTransaksiPreview }}</span>
                </p>
            </div>
            <a href="{{ route('transaksi-datang-langsung.index') }}"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-gray-300 rounded-lg text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Info banner --}}
    <div class="px-4 sm:px-6 py-2 bg-green-50 border-b border-green-100">
        <p class="text-xs text-green-800">
            <strong>Mode Dijemput:</strong> Simpan permintaan penjemputan muzakki. Detail zakat & pembayaran akan diisi oleh amil saat tiba di lokasi.
            Status awal: <span class="font-semibold">Pending — Menunggu Penjemputan</span>.
        </p>
    </div>

{{-- ══════════════════════════════════════════════════════════════
     FORM
══════════════════════════════════════════════════════════════ --}}
    <form id="formDijemput"
          action="{{ route('transaksi-dijemput.store') }}"
          method="POST"
          class="p-4 sm:p-6 space-y-5">
        @csrf

        {{-- Hidden: metode sudah fix dijemput, tidak perlu dipilih user --}}
        <input type="hidden" name="metode_penerimaan"  value="dijemput">
        <input type="hidden" name="tanggal_transaksi"  value="{{ $tanggalHariIni }}">
        <input type="hidden" name="waktu_transaksi"    value="{{ now()->format('H:i:s') }}">
        <input type="hidden" name="no_transaksi"       value="{{ $noTransaksiPreview }}">
        <input type="hidden" name="is_pembayaran_beras" value="0">

        {{-- ─── ERROR SUMMARY ─── --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-800">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-sm text-red-700 mt-1 space-y-0.5">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════
             SEKSI 1: DATA MUZAKKI
        ══════════════════════════════════════════════════════════ --}}
        <div>
            <h3 class="text-sm font-bold text-gray-800 mb-3 pb-1 border-b border-gray-100">1. Data Muzakki (Pemberi Zakat)</h3>

            <div class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="muzakki_nama" id="muzakkiNama"
                            value="{{ old('muzakki_nama') }}"
                            placeholder="Nama lengkap pemberi zakat"
                            class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('muzakki_nama') border-red-500 bg-red-50 @enderror">
                        @error('muzakki_nama')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIK --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            NIK <span class="text-xs text-gray-400 font-normal">(opsional, 16 digit)</span>
                        </label>
                        <input type="text" name="muzakki_nik"
                            value="{{ old('muzakki_nik') }}"
                            placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                            class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('muzakki_nik') border-red-500 bg-red-50 @enderror">
                        @error('muzakki_nik')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Telepon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon / WhatsApp</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">+62</span>
                            <input type="tel" name="muzakki_telepon"
                                value="{{ old('muzakki_telepon') }}"
                                placeholder="81234567890"
                                class="w-full pl-11 pr-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Untuk dihubungi amil penjemput</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-xs text-gray-400 font-normal">(untuk kwitansi)</span>
                        </label>
                        <input type="email" name="muzakki_email"
                            value="{{ old('muzakki_email') }}"
                            placeholder="email@contoh.com"
                            class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                    </div>
                </div>
                
                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="muzakki_alamat" id="muzakkiAlamat" rows="2"
                        placeholder="Alamat lengkap untuk penjemputan"
                        class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all resize-none">{{ old('muzakki_alamat') }}</textarea>
                    @error('muzakki_alamat')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>


        {{-- ══════════════════════════════════════════════════════════
             SEKSI 2: PENUGASAN AMIL
        ══════════════════════════════════════════════════════════ --}}
        <div>
            <h3 class="text-sm font-bold text-gray-800 mb-3 pb-1 border-b border-gray-100">2. Penugasan Amil Penjemput</h3>

            <div class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Pilih Amil - READONLY untuk amil yang login --}}
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Amil Penjemput <span class="text-red-500">*</span>
                        </label>
                        
                        @php
                            // Cek apakah user yang login adalah amil
                            $isAmilUser = Auth::user()->isAmil();
                            $currentAmilId = null;
                            $currentAmilName = null;
                            
                            if ($isAmilUser && Auth::user()->amil) {
                                $currentAmilId = Auth::user()->amil->id;
                                $currentAmilName = Auth::user()->amil->nama_lengkap;
                                if (Auth::user()->amil->kode_amil) {
                                    $currentAmilName .= ' (' . Auth::user()->amil->kode_amil . ')';
                                }
                            }
                        @endphp
                        
                        @if($isAmilUser && $currentAmilId)
                            {{-- Mode Amil: Tampilkan readonly dengan amil yang login --}}
                            <input type="hidden" name="amil_id" value="{{ $currentAmilId }}">
                            <div class="w-full px-4 py-2 text-sm bg-gray-100 border border-gray-300 rounded-xl text-gray-700">
                                <div class="flex items-center justify-between">
                                    <span>{{ $currentAmilName }}</span>
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Amil Bertugas</span>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Anda ditugaskan sebagai amil penjemput untuk transaksi ini.</p>
                        @else
                            {{-- Mode Admin Lembaga: Tampilkan dropdown pilihan amil --}}
                            <select name="amil_id" id="amilId"
                                class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all @error('amil_id') border-red-500 bg-red-50 @enderror">
                                <option value="">-- Pilih Amil yang Bertugas --</option>
                                @foreach($amilList as $amil)
                                    <option value="{{ $amil->id }}"
                                        {{ old('amil_id') == $amil->id ? 'selected' : '' }}>
                                        {{ $amil->nama_lengkap }}
                                        @if($amil->kode_amil) ({{ $amil->kode_amil }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('amil_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            @if($amilList->isEmpty())
                                <p class="mt-1 text-xs text-amber-600">
                                    Belum ada amil aktif terdaftar.
                                </p>
                            @endif
                        @endif
                    </div>

                    {{-- Tanggal Penjemputan yang Diinginkan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Penjemputan <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="date" name="tanggal_penjemputan"
                            value="{{ old('tanggal_penjemputan', $tanggalHariIni) }}"
                            min="{{ $tanggalHariIni }}"
                            class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all">
                        <p class="mt-1 text-xs text-gray-400">Tanggal yang diinginkan muzakki</p>
                    </div>
                </div>

                {{-- Info Amil terpilih (hanya untuk admin) --}}
                @if(!($isAmilUser && $currentAmilId))
                <div id="infoAmilTerpilih" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-3">
                    <p class="text-xs font-semibold text-blue-800" id="namaAmilTerpilih">-</p>
                    <p class="text-xs text-blue-700 mt-0.5">Amil ini akan mendapat notifikasi tugas penjemputan.</p>
                </div>
                @else
                {{-- Tampilkan info amil yang bertugas --}}
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
                    <p class="text-xs font-semibold text-blue-800">{{ $currentAmilName }}</p>
                    <p class="text-xs text-blue-700 mt-0.5">Anda akan mendapat notifikasi tugas penjemputan ini.</p>
                </div>
                @endif

                {{-- Catatan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan untuk Amil <span class="text-xs text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="keterangan" rows="2"
                        placeholder="Contoh: Patokan rumah di samping warung Bu Sari. Pagar besi hijau. Sebaiknya datang setelah Ashar."
                        class="w-full px-4 py-2 text-sm border border-gray-300 bg-white rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/10 transition-all resize-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             RINGKASAN SEBELUM SIMPAN
        ══════════════════════════════════════════════════════════ --}}
        <div id="boxRingkasan" class="hidden bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-xl p-3">
            <p class="text-xs font-bold text-green-800 uppercase tracking-wide mb-2">Ringkasan Permintaan Penjemputan</p>
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Muzakki</span>
                    <span class="font-semibold text-gray-900" id="ringSNama">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Amil Penjemput</span>
                    <span class="font-semibold text-gray-900" id="ringSAmil">-</span>
                </div>
                <div class="flex justify-between border-t border-green-200 pt-1 mt-1">
                    <span class="text-gray-600">Status Awal</span>
                    <span class="font-semibold text-amber-700">Menunggu Penjemputan</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Detail Zakat & Bayar</span>
                    <span class="font-semibold text-gray-500 italic">Diisi saat penjemputan</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════
             TOMBOL SUBMIT
        ══════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-gray-100">
            <a href="{{ route('transaksi-datang-langsung.index') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit" id="btnSubmit"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2
                    bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold
                    rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/50
                    hover:from-green-600 hover:to-emerald-700 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Permintaan Penjemputan
            </button>
        </div>

    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
// Data amil untuk info dinamis (hanya untuk admin)
const amilData = {
    @foreach($amilList as $amil)
    '{{ $amil->id }}': '{{ $amil->nama_lengkap }}{{ $amil->kode_amil ? " (".$amil->kode_amil.")" : "" }}',
    @endforeach
};

@php
    $isAmilUser = Auth::user()->isAmil();
    $currentAmilId = null;
    if ($isAmilUser && Auth::user()->amil) {
        $currentAmilId = Auth::user()->amil->id;
    }
@endphp

// Cek apakah user adalah amil
const isAmilUser = {{ $isAmilUser ? 'true' : 'false' }};
const currentAmilId = '{{ $currentAmilId }}';

// ── INFO AMIL TERPILIH (hanya untuk admin) ────────────────────
if (!isAmilUser) {
    const amilSelect = document.getElementById('amilId');
    if (amilSelect) {
        amilSelect.addEventListener('change', function () {
            const box  = document.getElementById('infoAmilTerpilih');
            const nama = document.getElementById('namaAmilTerpilih');
            if (this.value && amilData[this.value]) {
                nama.textContent = amilData[this.value];
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
            refreshRingkasan();
        });
    }
}

// ── INPUT MUZAKKI NAMA ────────────────────────────────────────
const muzakkiNamaInput = document.getElementById('muzakkiNama');
if (muzakkiNamaInput) {
    muzakkiNamaInput.addEventListener('input', refreshRingkasan);
}

// ── RINGKASAN ─────────────────────────────────────────────────
function refreshRingkasan() {
    const nama  = document.getElementById('muzakkiNama')?.value.trim() || '';
    
    let amilNama = '-';
    if (isAmilUser && currentAmilId) {
        amilNama = amilData[currentAmilId] || '-';
    } else {
        const amilSelect = document.getElementById('amilId');
        const amilVal = amilSelect ? amilSelect.value : '';
        amilNama = amilVal && amilData[amilVal] ? amilData[amilVal] : '-';
    }
    
    if (nama || (amilNama !== '-')) {
        const boxRingkasan = document.getElementById('boxRingkasan');
        const ringSNama = document.getElementById('ringSNama');
        const ringSAmil = document.getElementById('ringSAmil');
        
        if (boxRingkasan) boxRingkasan.classList.remove('hidden');
        if (ringSNama) ringSNama.textContent = nama || '-';
        if (ringSAmil) ringSAmil.textContent = amilNama;
    } else {
        const boxRingkasan = document.getElementById('boxRingkasan');
        if (boxRingkasan) boxRingkasan.classList.add('hidden');
    }
}

// ── FORM SUBMIT ───────────────────────────────────────────────
document.getElementById('formDijemput').addEventListener('submit', function (e) {
    const nama  = document.getElementById('muzakkiNama').value.trim();
    
    let amilId = null;
    if (isAmilUser && currentAmilId) {
        amilId = currentAmilId;
    } else {
        const amilSelect = document.getElementById('amilId');
        amilId = amilSelect ? amilSelect.value : null;
    }

    if (!nama) {
        e.preventDefault();
        alert('Nama muzakki wajib diisi.');
        document.getElementById('muzakkiNama').focus();
        return;
    }
    
    const alamat = document.getElementById('muzakkiAlamat')?.value.trim();
    if (!alamat) {
        e.preventDefault();
        alert('Alamat muzakki wajib diisi.');
        document.getElementById('muzakkiAlamat')?.focus();
        return;
    }
    
    if (!amilId) {
        e.preventDefault();
        alert('Pilih amil penjemput terlebih dahulu.');
        const amilSelect = document.getElementById('amilId');
        if (amilSelect) amilSelect.focus();
        return;
    }

    // Spin button
    const btn  = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = `<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
    </svg> Menyimpan...`;
});

// ── INIT ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Trigger refresh ringkasan
    refreshRingkasan();
    
    // Jika admin dan ada old value, trigger change
    @if(!$isAmilUser && old('amil_id'))
    const amilSelect = document.getElementById('amilId');
    if (amilSelect) {
        amilSelect.dispatchEvent(new Event('change'));
    }
    @endif
});
</script>
@endpush