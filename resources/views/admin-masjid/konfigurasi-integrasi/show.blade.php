@extends('layouts.app')

@section('title', 'Konfigurasi Integrasi')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ══════════════════════════════════════════════════════════════
         HEADER
    ══════════════════════════════════════════════════════════════ --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-5 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-900">Konfigurasi Integrasi</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola integrasi WhatsApp dan QRIS untuk masjid Anda</p>
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

        {{-- Success Message --}}
        @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <div class="p-4 sm:p-6 space-y-8">

            {{-- ══════════════════════════════════════════════════════════════
                 INFORMASI MASJID
            ══════════════════════════════════════════════════════════════ --}}
            <div>
                <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi Masjid</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nama Masjid</p>
                        <p class="text-sm text-gray-900 font-medium">{{ $masjid->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Kode Masjid</p>
                        <p class="text-sm text-gray-900 font-mono">{{ $masjid->kode_masjid ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Email</p>
                        <p class="text-sm text-gray-900">{{ $masjid->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Telepon</p>
                        <p class="text-sm text-gray-900">{{ $masjid->telepon ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Alamat</p>
                        <p class="text-sm text-gray-900">{{ $masjid->alamat_lengkap ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- ══════════════════════════════════════════════════════════════
                 BAGIAN 1 - WHATSAPP INTEGRATION
            ══════════════════════════════════════════════════════════════ --}}
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Integrasi WhatsApp</h3>
                        <p class="text-xs text-gray-500">Kirim notifikasi via WhatsApp</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($whatsapp->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                ✓ Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                ✗ Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">API Key</p>
                        <p class="text-sm text-gray-900 font-mono truncate">
                            {{ $whatsapp->api_key ? str_repeat('*', strlen($whatsapp->api_key) - 8) . substr($whatsapp->api_key, -8) : 'Tidak diatur' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nomor Pengirim</p>
                        <p class="text-sm text-gray-900">{{ $whatsapp->nomor_pengirim ?? 'Tidak diatur' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">URL API</p>
                        <p class="text-sm text-gray-900 font-mono truncate">{{ $whatsapp->api_url }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Nomor Tujuan Default</p>
                        <p class="text-sm text-gray-900">{{ $whatsapp->nomor_tujuan_default ?? 'Tidak diatur' }}</p>
                    </div>
                </div>

                <div class="mt-4 flex gap-3">
                    <button type="button" class="inline-flex items-center px-3 py-2 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition"
                            onclick="testWhatsappConnection()">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Test Koneksi
                    </button>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- ══════════════════════════════════════════════════════════════
                 BAGIAN 2 - QRIS INTEGRATION
            ══════════════════════════════════════════════════════════════ --}}
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Integrasi QRIS</h3>
                        <p class="text-xs text-gray-500">Tampilkan QRIS untuk pembayaran</p>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Toggle Button untuk QRIS Status --}}
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="toggle-qris-status" class="sr-only peer" {{ $qris->is_active ? 'checked' : '' }} onchange="toggleQrisStatus()">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                        @if($qris->is_active)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                ✓ Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                ✗ Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Gambar QRIS --}}
                @if($qris->qris_image_url)
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase block mb-3">Gambar QRIS</p>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="w-40 h-40 rounded-xl border-2 border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shadow-sm" id="qris-image-container">
                            <img src="{{ $qris->qris_image_url }}" 
                                 alt="QRIS" 
                                 class="w-full h-full object-contain p-2"
                                 loading="lazy"
                                 onerror="handleQrisImageError()">
                        </div>
                        <div class="flex flex-col gap-3">
                            <p class="text-sm font-medium text-gray-700">✓ Gambar QRIS sudah diunggah</p>
                            <div class="text-xs text-gray-500 space-y-1">
                                <p>Diperbarui: <span class="text-gray-700 font-medium">{{ optional($qris->updated_at)->format('d M Y H:i') ?? 'Belum ada' }}</span></p>
                            </div>
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
                        <p class="text-xs text-yellow-700 mt-2"><a href="{{ route('konfigurasi-integrasi.edit') }}" class="font-semibold hover:underline">Upload sekarang</a></p>
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
                            <span class="text-xs text-gray-600">{{ optional($qris->updated_at)->format('d M Y H:i') ?? 'Belum ada' }}</span>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Modal Test WhatsApp --}}
<div id="modal-test-whatsapp" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Koneksi WhatsApp</h3>
        
        <div class="space-y-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">API Key</label>
                <input type="password" id="test-api-key" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                       placeholder="Masukkan API Key">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Tujuan</label>
                <input type="text" id="test-nomor-tujuan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                       placeholder="628xxxxxxxxxx">
            </div>
        </div>

        <div id="test-result" class="mb-4 p-3 rounded-lg hidden"></div>

        <div class="flex gap-3">
            <button type="button" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition"
                    onclick="closeTestWhatsapp()">
                Batal
            </button>
            <button type="button" class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition"
                    onclick="sendTestWhatsapp()" id="btn-send-test">
                Kirim Test
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function testWhatsappConnection() {
    document.getElementById('modal-test-whatsapp').classList.remove('hidden');
    document.getElementById('modal-test-whatsapp').classList.add('flex');
}

function closeTestWhatsapp() {
    document.getElementById('modal-test-whatsapp').classList.add('hidden');
    document.getElementById('modal-test-whatsapp').classList.remove('flex');
    document.getElementById('test-result').classList.add('hidden');
    document.getElementById('test-api-key').value = '';
    document.getElementById('test-nomor-tujuan').value = '';
}

async function sendTestWhatsapp() {
    const apiKey = document.getElementById('test-api-key').value;
    const nomorTujuan = document.getElementById('test-nomor-tujuan').value;
    const resultDiv = document.getElementById('test-result');
    const btn = document.getElementById('btn-send-test');

    if (!apiKey || !nomorTujuan) {
        resultDiv.classList.remove('hidden');
        resultDiv.className = 'p-3 rounded-lg bg-red-50 border border-red-200';
        resultDiv.innerHTML = '<p class="text-sm text-red-800">⚠ Mohon isi semua field</p>';
        resultDiv.classList.remove('hidden');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = 'Mengirim...';

    try {
        const response = await fetch('{{ route("konfigurasi-integrasi.test-whatsapp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                api_key: apiKey,
                nomor_tujuan: nomorTujuan
            })
        });

        const data = await response.json();

        resultDiv.classList.remove('hidden');
        if (data.success) {
            resultDiv.className = 'p-3 rounded-lg bg-emerald-50 border border-emerald-200';
            resultDiv.innerHTML = `<p class="text-sm text-emerald-800 font-medium">✓ ${data.message}</p>`;
        } else {
            resultDiv.className = 'p-3 rounded-lg bg-red-50 border border-red-200';
            resultDiv.innerHTML = `<p class="text-sm text-red-800 font-medium">✗ ${data.message}</p>`;
        }
    } catch (error) {
        resultDiv.classList.remove('hidden');
        resultDiv.className = 'p-3 rounded-lg bg-red-50 border border-red-200';
        resultDiv.innerHTML = `<p class="text-sm text-red-800">❌ Terjadi kesalahan: ${error.message}</p>`;
    } finally {
        btn.disabled = false;
        btn.innerHTML = 'Kirim Test';
    }
}

// ═════════════════════════════════════════════════════════════════
// HANDLE QRIS IMAGE ERROR
// ═════════════════════════════════════════════════════════════════
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
            // Update toggle position
            toggle.checked = data.is_active;
            
            // Update status text - cari span status badge terdekat
            const statusSpan = toggle.closest('.flex').querySelectorAll('span')[1];
            if (statusSpan) {
                if (data.is_active) {
                    statusSpan.innerHTML = '✓ Aktif';
                    statusSpan.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200';
                } else {
                    statusSpan.innerHTML = '✗ Tidak Aktif';
                    statusSpan.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200';
                }
            }
            
            // Show success notification
            showNotification('Status QRIS berhasil diubah', 'success');
        } else {
            toggle.checked = !toggle.checked;
            showNotification(data.message || 'Gagal mengubah status', 'error');
        }
    } catch (error) {
        toggle.checked = !toggle.checked;
        console.error('Error:', error);
        showNotification('Terjadi kesalahan: ' + error.message, 'error');
    }
}

// Helper function untuk notification
function showNotification(message, type = 'info') {
    const bgColor = type === 'success' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200';
    const textColor = type === 'success' ? 'text-emerald-800' : 'text-red-800';
    const iconColor = type === 'success' ? 'text-emerald-600' : 'text-red-600';
    const icon = type === 'success' ? '✓' : '✗';
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} border rounded-lg p-4 shadow-lg z-50 max-w-sm`;
    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <span class="${iconColor} font-bold text-lg">${icon}</span>
            <p class="text-sm font-medium ${textColor}">${message}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endpush