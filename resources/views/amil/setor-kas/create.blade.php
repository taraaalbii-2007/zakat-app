{{-- resources/views/amil/setor-kas/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Buat Setoran Kas')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Form Setoran Kas</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Isi detail setoran kas untuk periode yang ingin disetor</p>
        </div>

        <form action="{{ route('amil.setor-kas.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-6">
            @csrf

            {{-- Info Amil --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <div>
                    <p class="text-xs font-semibold text-blue-900">Amil Penyetor</p>
                    <p class="text-sm font-medium text-blue-700">{{ $amil->nama_lengkap ?? auth()->user()->username }}</p>
                    <p class="text-xs text-blue-500">{{ $amil->masjid->nama ?? '-' }}</p>
                </div>
            </div>

            {{-- SECTION 1: Info Setoran --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">1</span>
                    Informasi Setoran
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tanggal Setor --}}
                    <div>
                        <label for="tanggal_setor" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Setor <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_setor" id="tanggal_setor"
                            value="{{ old('tanggal_setor', date('Y-m-d')) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_setor') border-red-500 @enderror">
                        @error('tanggal_setor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- No Setor (Auto) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Setoran</label>
                        <input type="text" value="(otomatis digenerate)" disabled
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl bg-gray-50 cursor-not-allowed text-gray-400">
                    </div>

                    {{-- Periode Dari --}}
                    <div>
                        <label for="periode_dari" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Periode Dari <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="periode_dari" id="periode_dari"
                            value="{{ old('periode_dari', $periodeDari) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('periode_dari') border-red-500 @enderror">
                        @error('periode_dari') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Periode Sampai --}}
                    <div>
                        <label for="periode_sampai" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Periode Sampai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="periode_sampai" id="periode_sampai"
                            value="{{ old('periode_sampai', $periodeSampai) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('periode_sampai') border-red-500 @enderror">
                        @error('periode_sampai') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tombol hitung rekap --}}
                <div class="mt-3">
                    <button type="button" id="btn-hitung-rekap"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Hitung Rekap Kas Periode
                    </button>
                </div>
            </div>

            {{-- SECTION 2: Rekap & Jumlah --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">2</span>
                    Rekap Kas & Jumlah Disetor
                </h3>

                {{-- Rekap dari Kas Harian --}}
                <div id="rekap-kas-container" class="{{ $rekapKas ? '' : 'hidden' }} mb-4">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <p class="text-xs font-semibold text-green-900 mb-3 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Rekap Kas Periode
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="bg-white rounded-lg p-3 border border-green-100">
                                <p class="text-xs text-gray-500">Datang Langsung</p>
                                <p class="text-base font-bold text-gray-800" id="rekap-datang-langsung">
                                    {{ $rekapKas ? 'Rp '.number_format($rekapKas['datang_langsung'],0,',','.') : '-' }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-green-100">
                                <p class="text-xs text-gray-500">Dijemput</p>
                                <p class="text-base font-bold text-gray-800" id="rekap-dijemput">
                                    {{ $rekapKas ? 'Rp '.number_format($rekapKas['dijemput'],0,',','.') : '-' }}
                                </p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-green-100">
                                <p class="text-xs text-gray-500 font-semibold">Total</p>
                                <p class="text-base font-bold text-primary" id="rekap-total">
                                    {{ $rekapKas ? $rekapKas['total_fmt'] : '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="btn-pakai-rekap"
                                onclick="pakaiRekap()"
                                class="text-xs font-medium text-green-700 hover:text-green-900 underline">
                                Gunakan total rekap sebagai jumlah disetor
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Input Jumlah --}}
                <div>
                    <label for="jumlah_disetor" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jumlah Disetor <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">Rp</span>
                        <input type="number" name="jumlah_disetor" id="jumlah_disetor"
                            value="{{ old('jumlah_disetor', $rekapKas['total'] ?? '') }}"
                            placeholder="0"
                            min="0"
                            step="1000"
                            class="block w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('jumlah_disetor') border-red-500 @enderror">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Isi manual atau gunakan tombol rekap di atas</p>
                    @error('jumlah_disetor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- SECTION 3: Bukti & Tanda Tangan --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">3</span>
                    Bukti & Tanda Tangan
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Foto Bukti --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Foto Uang / Bukti Setor</label>
                        <div class="space-y-2">
                            <div id="foto-preview" class="h-40 rounded-xl border-2 border-dashed border-gray-300 hover:border-primary/50 flex items-center justify-center overflow-hidden bg-gray-50 transition-colors">
                                <div class="text-center text-gray-400">
                                    <svg class="mx-auto w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs">Belum ada foto</p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <input type="file" name="bukti_foto" id="bukti_foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
                                <label for="bukti_foto" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Pilih Foto
                                </label>
                                <button type="button" id="btn-remove-foto" onclick="removeFoto()" class="hidden px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-400">JPG/PNG, maks 5MB</p>
                        </div>
                        @error('bukti_foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Signature Pad --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanda Tangan Amil</label>
                        <div class="space-y-2">
                            <canvas id="signature-pad"
                                class="block w-full h-40 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 cursor-crosshair touch-none">
                            </canvas>
                            <input type="hidden" name="tanda_tangan_amil" id="tanda_tangan_amil_input">
                            <div class="flex gap-2">
                                <button type="button" onclick="clearSignature()"
                                    class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                            <p class="text-xs text-gray-400">Tanda tangan di area di atas</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: Keterangan --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">4</span>
                    Keterangan Tambahan
                </h3>
                <textarea name="keterangan" id="keterangan" rows="3"
                    placeholder="Catatan tambahan mengenai setoran ini (opsional)..."
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('amil.setor-kas.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" id="btn-submit"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Submit Setoran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Foto Preview ───────────────────────────────────────────────────────────
function previewFoto(input) {
    if (!input.files[0]) return;
    if (input.files[0].size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5MB'); input.value = ''; return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        const p = document.getElementById('foto-preview');
        p.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-contain">`;
        document.getElementById('btn-remove-foto').classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}
function removeFoto() {
    document.getElementById('bukti_foto').value = '';
    document.getElementById('foto-preview').innerHTML = `
        <div class="text-center text-gray-400">
            <svg class="mx-auto w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-xs">Belum ada foto</p>
        </div>`;
    document.getElementById('btn-remove-foto').classList.add('hidden');
}

// ── Signature Pad ─────────────────────────────────────────────────────────
const canvas = document.getElementById('signature-pad');
const ctx    = canvas.getContext('2d');
let drawing  = false;
let lastX = 0, lastY = 0;

function resizeCanvas() {
    const rect = canvas.getBoundingClientRect();
    canvas.width  = rect.width;
    canvas.height = rect.height;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    if (e.touches) {
        return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
    }
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
}

canvas.addEventListener('mousedown', e => { drawing = true; const p = getPos(e); lastX = p.x; lastY = p.y; });
canvas.addEventListener('mousemove', e => {
    if (!drawing) return;
    const p = getPos(e);
    ctx.beginPath(); ctx.moveTo(lastX, lastY);
    ctx.lineTo(p.x, p.y);
    ctx.strokeStyle = '#1e293b'; ctx.lineWidth = 2; ctx.lineCap = 'round';
    ctx.stroke();
    lastX = p.x; lastY = p.y;
});
canvas.addEventListener('mouseup', () => { drawing = false; saveSignature(); });
canvas.addEventListener('mouseleave', () => { drawing = false; });
canvas.addEventListener('touchstart', e => { e.preventDefault(); drawing = true; const p = getPos(e); lastX = p.x; lastY = p.y; });
canvas.addEventListener('touchmove', e => {
    e.preventDefault(); if (!drawing) return;
    const p = getPos(e);
    ctx.beginPath(); ctx.moveTo(lastX, lastY);
    ctx.lineTo(p.x, p.y);
    ctx.strokeStyle = '#1e293b'; ctx.lineWidth = 2; ctx.lineCap = 'round';
    ctx.stroke();
    lastX = p.x; lastY = p.y;
});
canvas.addEventListener('touchend', () => { drawing = false; saveSignature(); });

function saveSignature() {
    document.getElementById('tanda_tangan_amil_input').value = canvas.toDataURL('image/png');
}
function clearSignature() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById('tanda_tangan_amil_input').value = '';
}

// ── Hitung Rekap Kas ──────────────────────────────────────────────────────
let rekapData = null;

document.getElementById('btn-hitung-rekap').addEventListener('click', async function() {
    const dari   = document.getElementById('periode_dari').value;
    const sampai = document.getElementById('periode_sampai').value;
    if (!dari || !sampai) {
        alert('Pilih periode terlebih dahulu'); return;
    }
    this.disabled = true;
    this.innerHTML = '<svg class="w-4 h-4 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg> Menghitung...';

    try {
        const res = await fetch('{{ route("amil.setor-kas.api.hitung-rekap") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ periode_dari: dari, periode_sampai: sampai })
        });
        const data = await res.json();
        if (data.error) { alert(data.error); return; }

        rekapData = data;
        document.getElementById('rekap-datang-langsung').textContent = data.datang_langsung_fmt;
        document.getElementById('rekap-dijemput').textContent        = data.dijemput_fmt;
        document.getElementById('rekap-total').textContent           = data.total_fmt;
        document.getElementById('rekap-kas-container').classList.remove('hidden');
    } catch(e) {
        alert('Gagal menghitung rekap');
    } finally {
        this.disabled = false;
        this.innerHTML = '<svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Hitung Rekap Kas Periode';
    }
});

function pakaiRekap() {
    if (rekapData) {
        document.getElementById('jumlah_disetor').value = rekapData.total;
    }
}

// Periode date constraint
document.getElementById('periode_dari').addEventListener('change', function() {
    document.getElementById('periode_sampai').min = this.value;
});
</script>
@endpush