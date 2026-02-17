@extends('layouts.app')

@section('title', 'Dashboard Amil')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(45, 105, 54, 0.08);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -8px rgba(45, 105, 54, 0.15);
    }

    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2d6936 0%, #7cb342 100%);
    }

    .glass-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(45, 105, 54, 0.1);
        border-radius: 16px;
        box-shadow: 0 4px 16px -4px rgba(45, 105, 54, 0.08);
    }

    .quick-action-btn {
        background: white;
        border: 2px solid rgba(45, 105, 54, 0.1);
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
        text-align: center;
        cursor: pointer;
    }

    .quick-action-btn:hover {
        border-color: #2d6936;
        background: #f8fdf9;
        transform: translateY(-2px);
    }

    .reminder-item {
        background: white;
        border-left: 4px solid #7cb342;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.2s ease;
    }

    .reminder-item:hover {
        background: #f8fdf9;
        transform: translateX(4px);
    }

    .badge-status {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .badge-info {
        background: #d1ecf1;
        color: #0c5460;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Hero Section --}}
    <div class="bg-gradient-primary rounded-2xl p-8 text-white shadow-nz-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Dashboard Amil</h1>
                <p class="text-white/90 text-lg">{{ $masjid->nama }}</p>
                <p class="text-white/70 text-sm mt-1">Selamat datang, {{ $user->nama }}</p>
            </div>
            <div class="text-right">
                <p class="text-white/70 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-white font-semibold text-xl" id="liveTime">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach($quickStats as $stat)
        <div class="glass-panel p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    @if($stat['icon'] === 'users')
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    @elseif($stat['icon'] === 'user-group')
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @else
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @endif
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-600">{{ is_numeric($stat['value']) ? $stat['value'] : '' }}</p>
                    @if(!is_numeric($stat['value']))
                    <p class="text-lg font-bold text-success-600">{{ $stat['value'] }}</p>
                    @endif
                    <p class="text-sm text-neutral-500">{{ $stat['label'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Main Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Penerimaan Bulan Ini --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                    </svg>
                </div>
                <span class="badge-status badge-success">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($stats['total_penerimaan_bulan_ini'], 0, ',', '.') }}</p>
            <p class="text-sm text-neutral-500 font-medium">Total Penerimaan</p>
        </div>

        {{-- Penyaluran Bulan Ini --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
                <span class="badge-status badge-warning">Bulan Ini</span>
            </div>
            <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($stats['total_penyaluran_bulan_ini'], 0, ',', '.') }}</p>
            <p class="text-sm text-neutral-500 font-medium">Total Penyaluran</p>
        </div>

        {{-- Saldo --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="badge-status badge-info">Saat Ini</span>
            </div>
            <p class="text-2xl font-bold text-success-600">Rp {{ number_format($stats['saldo_saat_ini'], 0, ',', '.') }}</p>
            <p class="text-sm text-neutral-500 font-medium">Saldo Zakat</p>
        </div>
    </div>

    {{-- Quick Actions & Reminders --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Quick Actions --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Aksi Cepat
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-3">
                    <a href="" class="quick-action-btn">
                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <p class="text-sm font-semibold text-neutral-700">Input Penerimaan</p>
                    </a>

                    <a href="" class="quick-action-btn">
                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        <p class="text-sm font-semibold text-neutral-700">Input Penyaluran</p>
                    </a>

                    <a href="ass="quick-action-btn">
                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <p class="text-sm font-semibold text-neutral-700">Data Muzakki</p>
                    </a>

                    <a href="lass="quick-action-btn">
                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-sm font-semibold text-neutral-700">Data Mustahik</p>
                    </a>

                    <a href="lass="quick-action-btn">
                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-neutral-700">Laporan Harian</p>
                    </a>

                    <a href=" class="quick-action-btn">
                        <svg class="w-8 h-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-neutral-700">Kalkulator Nisab</p>
                    </a>
                </div>
            </div>
        </div>

        {{-- Reminders --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Pengingat Tugas
                </h3>
            </div>
            <div class="p-4 space-y-2">
                @forelse($reminders as $reminder)
                <div class="reminder-item">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-sm text-neutral-700">{{ $reminder }}</span>
                    </div>
                </div>
                @empty
                <p class="text-center text-neutral-400 py-8">Tidak ada pengingat saat ini</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Harga Nisab & Jenis Zakat --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Harga Nisab --}}
        @if($hargaTerkini)
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700">Harga Nisab Terkini</h3>
                <p class="text-xs text-neutral-500 mt-1">Update: {{ $hargaTerkini->tanggal->translatedFormat('d F Y') }}</p>
            </div>
            <div class="p-4 space-y-3">
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-neutral-600 font-medium">Harga Emas (per gram)</span>
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-yellow-700 mb-1">Rp {{ number_format($hargaTerkini->harga_emas_pergram, 0, ',', '.') }}</p>
                    <div class="mt-3 pt-3 border-t border-yellow-200">
                        <p class="text-xs text-neutral-600 mb-1">Nisab (85 gram)</p>
                        <p class="text-lg font-bold text-yellow-800">Rp {{ number_format($hargaTerkini->harga_emas_pergram * 85, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-neutral-600 font-medium">Harga Perak (per gram)</span>
                        <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-700 mb-1">Rp {{ number_format($hargaTerkini->harga_perak_pergram, 0, ',', '.') }}</p>
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <p class="text-xs text-neutral-600 mb-1">Nisab (595 gram)</p>
                        <p class="text-lg font-bold text-gray-800">Rp {{ number_format($hargaTerkini->harga_perak_pergram * 595, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Jenis Zakat --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700">Jenis Zakat Aktif</h3>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto">
                @forelse($jenisZakat as $jz)
                <div class="flex items-center justify-between py-3 px-3 hover:bg-primary-50 rounded-lg transition-colors border-b last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                            <span class="text-primary-700 font-bold text-sm">{{ $jz->kode }}</span>
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-neutral-800">{{ $jz->nama }}</p>
                            @if($jz->deskripsi)
                            <p class="text-xs text-neutral-400 mt-0.5">{{ Str::limit($jz->deskripsi, 50) }}</p>
                            @endif
                        </div>
                    </div>
                    @if($jz->nominal_minimal)
                    <div class="text-right">
                        <p class="text-xs text-neutral-500">Min</p>
                        <p class="text-sm font-semibold text-primary-700">Rp {{ number_format($jz->nominal_minimal, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-neutral-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-neutral-400">Belum ada jenis zakat</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('liveTime').textContent = timeString;
    }
    
    updateClock();
    setInterval(updateClock, 1000);
});
</script>
@endpush