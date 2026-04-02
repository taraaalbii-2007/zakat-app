@extends('layouts.app')

@section('title', 'Konfigurasi Integrasi')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Konfigurasi Integrasi</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola integrasi QRIS untuk lembaga Anda</p>
                </div>
                <a href="{{ route('konfigurasi-integrasi.edit') }}"
                   class="inline-flex items-center px-3 sm:px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Konfigurasi
                </a>
            </div>
        </div>

        <div class="p-4 sm:p-6 space-y-8">

            {{-- INFORMASI MASJID --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi Lembaga</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Lembaga</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $lembaga->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kode Lembaga</p>
                        <p class="text-sm text-gray-900 font-mono">{{ $lembaga->kode_lembaga ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email</p>
                        <p class="text-sm text-gray-900">{{ $lembaga->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Telepon</p>
                        <p class="text-sm text-gray-900">{{ $lembaga->telepon ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Alamat</p>
                        <p class="text-sm text-gray-900">{{ $lembaga->alamat_lengkap ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- QRIS INTEGRATION --}}
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Integrasi QRIS</h3>
                        <p class="text-xs text-gray-500">Tampilkan QRIS untuk pembayaran</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="toggle-qris-status" class="sr-only peer"
                                   {{ $qris->is_active ? 'checked' : '' }}
                                   onchange="toggleQrisStatus()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                        <span id="qris-status-badge"
                              class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border
                                     {{ $qris->is_active ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                            {{ $qris->is_active ? '✓ Aktif' : '✗ Tidak Aktif' }}
                        </span>
                    </div>
                </div>

                {{-- Gambar QRIS --}}
                @if($qris->qris_image_url)
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase block mb-3">Gambar QRIS</p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-40 h-40 rounded-xl border-2 border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shadow-sm"
                             id="qris-image-container">
                            <img src="{{ $qris->qris_image_url }}"
                                 alt="QRIS"
                                 class="w-full h-full object-contain p-2"
                                 loading="lazy"
                                 onerror="handleQrisImageError()">
                        </div>
                        <div class="flex flex-col gap-3">
                            <p class="text-sm font-medium text-gray-700">✓ Gambar QRIS sudah diunggah</p>
                            <p class="text-xs text-gray-500">
                                Diperbarui:
                                <span class="text-gray-700 font-medium">
                                    {{ optional($qris->updated_at)->format('d M Y H:i') ?? 'Belum ada' }}
                                </span>
                            </p>
                            <a href="{{ route('konfigurasi-integrasi.edit') }}"
                               class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition w-fit">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Ubah Gambar QRIS
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800">Gambar QRIS belum diunggah</p>
                        <p class="text-xs text-yellow-700 mt-1">
                            <a href="{{ route('konfigurasi-integrasi.edit') }}" class="font-semibold hover:underline">Upload sekarang</a>
                        </p>
                    </div>
                </div>
                @endif

                {{-- Info Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            <span class="font-medium">Status QRIS:</span><br>
                            @if($qris->is_active && $qris->qris_image_url)
                                <span class="text-emerald-700 font-medium">✓ Siap digunakan</span>
                            @elseif($qris->is_active && !$qris->qris_image_url)
                                <span class="text-yellow-700 font-medium">⚠ Aktif tapi belum ada gambar</span>
                            @else
                                <span class="text-red-700 font-medium">✗ Tidak aktif</span>
                            @endif
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-sm text-gray-900">
                            <span class="font-medium">Terakhir diupdate:</span><br>
                            <span class="text-xs text-gray-600">
                                {{ optional($qris->updated_at)->format('d M Y H:i') ?? 'Belum ada' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function handleQrisImageError() {
    const container = document.getElementById('qris-image-container');
    container.innerHTML = `
        <div class="flex flex-col items-center justify-center gap-2 p-4">
            <span class="text-sm font-medium text-red-600 text-center">Gambar tidak ditemukan</span>
            <p class="text-xs text-red-500 text-center">File mungkin sudah dihapus</p>
        </div>
    `;
}

async function toggleQrisStatus() {
    const toggle = document.getElementById('toggle-qris-status');
    const badge  = document.getElementById('qris-status-badge');

    try {
        const response = await fetch('{{ route("konfigurasi-integrasi.toggle-qris") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            toggle.checked = data.is_active;

            if (data.is_active) {
                badge.textContent = '✓ Aktif';
                badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border bg-emerald-100 text-emerald-800 border-emerald-200';
            } else {
                badge.textContent = '✗ Tidak Aktif';
                badge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border bg-red-100 text-red-800 border-red-200';
            }

            showNotification('Status QRIS berhasil diubah', 'success');
        } else {
            toggle.checked = !toggle.checked;
            showNotification(data.message || 'Gagal mengubah status', 'error');
        }
    } catch (error) {
        toggle.checked = !toggle.checked;
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    }
}

function showNotification(message, type = 'info') {
    const isSuccess = type === 'success';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${isSuccess ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200'} border rounded-lg p-4 shadow-lg z-50 max-w-sm`;
    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <span class="${isSuccess ? 'text-emerald-600' : 'text-red-600'} font-bold text-lg">${isSuccess ? '✓' : '✗'}</span>
            <p class="text-sm font-medium ${isSuccess ? 'text-emerald-800' : 'text-red-800'}">${message}</p>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}
</script>
@endpush