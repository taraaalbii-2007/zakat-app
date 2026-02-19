{{-- resources/views/amil/kunjungan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Tambah Jadwal Kunjungan')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-base sm:text-lg font-semibold text-gray-900">Buat Jadwal Kunjungan</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Isi detail rencana kunjungan mustahik</p>
        </div>

        <form action="{{ route('amil.kunjungan.store') }}" method="POST" class="p-4 sm:p-6 space-y-5">
            @csrf

            {{-- SECTION 1: Mustahik --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-900 mb-2 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary text-white text-xs">1</span>
                    Pilih Mustahik
                </h3>

                <div class="space-y-3">
                    {{-- Select Dropdown --}}
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
                                        {{ old('mustahik_id') == $m->id ? 'selected' : '' }}>
                                        {{ $m->nama_lengkap }}{{ $m->no_registrasi ? ' · ' . $m->no_registrasi : '' }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Chevron icon --}}
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

                    {{-- Info mustahik terpilih --}}
                    <div id="mustahik-info" class="{{ old('mustahik_id') ? '' : 'hidden' }} bg-blue-50 border border-blue-200 rounded-xl p-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-200 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-blue-900" id="info-nama">-</p>
                                <p class="text-xs text-blue-700 mt-0.5" id="info-no-reg">-</p>
                                <p class="text-xs text-blue-600 mt-1 flex items-start gap-1">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span id="info-alamat-text">-</span>
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
                    {{-- Tanggal --}}
                    <div class="sm:col-span-2">
                        <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Tanggal Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan"
                            value="{{ old('tanggal_kunjungan', request('tanggal', date('Y-m-d'))) }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('tanggal_kunjungan') border-red-500 @enderror">
                        @error('tanggal_kunjungan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Waktu Mulai --}}
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai"
                            value="{{ old('waktu_mulai') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_mulai') border-red-500 @enderror">
                        @error('waktu_mulai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Waktu Selesai --}}
                    <div>
                        <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-1.5">Waktu Selesai (Est.)</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai"
                            value="{{ old('waktu_selesai') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('waktu_selesai') border-red-500 @enderror">
                        @error('waktu_selesai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tujuan --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tujuan Kunjungan <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach([
                                'verifikasi' => ['label' => 'Verifikasi',  'color' => 'indigo', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'penyaluran' => ['label' => 'Penyaluran', 'color' => 'green',  'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                'monitoring'  => ['label' => 'Monitoring',  'color' => 'yellow', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'],
                                'lainnya'     => ['label' => 'Lainnya',     'color' => 'gray',   'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ] as $val => $opt)
                            <label class="tujuan-card relative flex flex-col items-center gap-1.5 p-3 border-2 rounded-xl cursor-pointer
                                transition-all duration-150 select-none
                                border-gray-200
                                hover:scale-[1.03] hover:shadow-md
                                hover:border-{{ $opt['color'] }}-400 hover:bg-{{ $opt['color'] }}-50
                                has-[:checked]:border-{{ $opt['color'] }}-500 has-[:checked]:bg-{{ $opt['color'] }}-50 has-[:checked]:shadow-md has-[:checked]:scale-[1.03]">
                                <input type="radio" name="tujuan" value="{{ $val }}" class="sr-only"
                                    {{ old('tujuan') === $val ? 'checked' : '' }}>
                                <svg class="w-5 h-5 text-{{ $opt['color'] }}-500 transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all @error('catatan') border-red-500 @enderror">{{ old('catatan') }}</textarea>
                @error('catatan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('amil.kunjungan.index') }}"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" name="langsung_selesai" value="0"
                    class="inline-flex items-center justify-center px-5 py-2.5 border border-primary text-sm font-medium rounded-xl text-primary bg-white hover:bg-primary/5 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Simpan Jadwal
                </button>
                <button type="submit" name="langsung_selesai" value="1"
                    class="inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan & Isi Hasil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function onMustahikChange(select) {
    const option   = select.options[select.selectedIndex];
    const infoBox  = document.getElementById('mustahik-info');

    if (!select.value) {
        infoBox.classList.add('hidden');
        return;
    }

    document.getElementById('info-nama').textContent      = option.dataset.nama    || '-';
    document.getElementById('info-no-reg').textContent    = 'No. Reg: ' + (option.dataset.noreg || '-');
    document.getElementById('info-alamat-text').textContent = option.dataset.alamat || '-';
    infoBox.classList.remove('hidden');
}

// Restore state on page load (jika ada old value)
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('mustahik_id');
    if (select && select.value) {
        onMustahikChange(select);
    }
});
</script>
@endpush