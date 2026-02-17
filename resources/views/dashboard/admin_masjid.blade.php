@extends('layouts.app')

@section('title', 'Dashboard Admin Masjid')

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
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Hero Section --}}
    <div class="bg-gradient-primary rounded-2xl p-8 text-white shadow-nz-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $masjid->nama }}</h1>
                <p class="text-white/80">Kode Masjid: {{ $masjid->kode_masjid }}</p>
            </div>
            <div class="text-right">
                <p class="text-white/70 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-white font-semibold" id="liveTime">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
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
            </div>
            <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($stats['total_penerimaan_bulan_ini'], 0, ',', '.') }}</p>
            <p class="text-sm text-neutral-500 font-medium">Penerimaan Bulan Ini</p>
        </div>

        {{-- Penyaluran Bulan Ini --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($stats['total_penyaluran_bulan_ini'], 0, ',', '.') }}</p>
            <p class="text-sm text-neutral-500 font-medium">Penyaluran Bulan Ini</p>
        </div>

        {{-- Saldo --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-success-600">Rp {{ number_format($stats['saldo_zakat'], 0, ',', '.') }}</p>
            <p class="text-sm text-neutral-500 font-medium">Saldo Saat Ini</p>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-panel p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['jumlah_muzakki'] }}</p>
                    <p class="text-sm text-neutral-500">Muzakki</p>
                </div>
            </div>
        </div>

        <div class="glass-panel p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['jumlah_mustahik'] }}</p>
                    <p class="text-sm text-neutral-500">Mustahik</p>
                </div>
            </div>
        </div>

        <div class="glass-panel p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['total_amil'] }}</p>
                    <p class="text-sm text-neutral-500">Total Amil</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Harga Nisab --}}
    @if($hargaTerkini)
    <div class="glass-panel p-6">
        <h3 class="text-lg font-bold text-neutral-800 mb-4">Harga Nisab Terkini</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-primary-50 rounded-xl p-4">
                <p class="text-sm text-neutral-500 mb-1">Harga Emas (per gram)</p>
                <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($hargaTerkini->harga_emas_pergram, 0, ',', '.') }}</p>
                <p class="text-xs text-neutral-400 mt-2">Nisab: 85 gram = Rp {{ number_format($hargaTerkini->harga_emas_pergram * 85, 0, ',', '.') }}</p>
            </div>
            <div class="bg-primary-50 rounded-xl p-4">
                <p class="text-sm text-neutral-500 mb-1">Harga Perak (per gram)</p>
                <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($hargaTerkini->harga_perak_pergram, 0, ',', '.') }}</p>
                <p class="text-xs text-neutral-400 mt-2">Nisab: 595 gram = Rp {{ number_format($hargaTerkini->harga_perak_pergram * 595, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Trend Chart --}}
    @if($trendPenerimaan->count() > 0)
    <div class="glass-panel p-6">
        <h3 class="text-lg font-bold text-neutral-800 mb-4">Trend Penerimaan & Penyaluran (6 Bulan)</h3>
        <div style="height: 300px;">
            <canvas id="chartTrend"></canvas>
        </div>
    </div>
    @endif

    {{-- Jenis Zakat & Kategori Mustahik --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Jenis Zakat --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700">Jenis Zakat Aktif</h3>
            </div>
            <div class="p-4">
                @forelse($jenisZakatAktif as $jz)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="font-semibold text-sm">{{ $jz->nama }}</p>
                        <p class="text-xs text-neutral-400">{{ $jz->kode }}</p>
                    </div>
                    @if($jz->nominal_minimal)
                    <span class="text-xs text-neutral-500">Min: Rp {{ number_format($jz->nominal_minimal, 0, ',', '.') }}</span>
                    @endif
                </div>
                @empty
                <p class="text-center text-neutral-400 py-8">Belum ada jenis zakat</p>
                @endforelse
            </div>
        </div>

        {{-- Kategori Mustahik --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700">Kategori Mustahik</h3>
            </div>
            <div class="p-4">
                @forelse($kategoriMustahik as $km)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div>
                        <p class="font-semibold text-sm">{{ $km->nama }}</p>
                        <p class="text-xs text-neutral-400">{{ $km->kode }}</p>
                    </div>
                    @if($km->persentase_default)
                    <span class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-xs">{{ $km->persentase_default }}%</span>
                    @endif
                </div>
                @empty
                <p class="text-center text-neutral-400 py-8">Belum ada kategori mustahik</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live Clock
    setInterval(() => {
        document.getElementById('liveTime').textContent = new Date().toLocaleTimeString('id-ID');
    }, 1000);

    // Chart Trend
    const trendData = @json($trendPenerimaan);
    if (trendData.length > 0) {
        new Chart(document.getElementById('chartTrend'), {
            type: 'line',
            data: {
                labels: trendData.map(i => i.bulan),
                datasets: [
                    {
                        label: 'Penerimaan',
                        data: trendData.map(i => i.penerimaan),
                        borderColor: '#2d6936',
                        backgroundColor: '#2d693620',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Penyaluran',
                        data: trendData.map(i => i.penyaluran),
                        borderColor: '#7cb342',
                        backgroundColor: '#7cb34220',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush