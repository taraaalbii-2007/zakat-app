@extends('layouts.app')

@section('title', 'Dashboard Superadmin')

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

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2d6936;
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
                <h1 class="text-3xl font-bold mb-2">Dashboard Superadmin</h1>
                <p class="text-white/80">Kelola seluruh sistem zakat digital</p>
            </div>
            <div class="text-right">
                <p class="text-white/70 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-white font-semibold" id="liveTime">{{ now()->format('H:i:s') }}</p>
            </div>
        </div>
    </div>

    {{-- Main Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Masjid --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-semibold">{{ $stats['masjid_aktif'] }} Aktif</span>
            </div>
            <p class="stat-number">{{ number_format($stats['total_masjid']) }}</p>
            <p class="text-sm text-neutral-500 font-medium">Total Masjid</p>
        </div>

        {{-- Total Pengguna --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-number">{{ number_format($stats['total_pengguna']) }}</p>
            <p class="text-sm text-neutral-500 font-medium">Total Pengguna</p>
        </div>

        {{-- Admin Masjid --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-number">{{ number_format($stats['total_admin_masjid']) }}</p>
            <p class="text-sm text-neutral-500 font-medium">Admin Masjid</p>
        </div>

        {{-- Total Amil --}}
        <div class="stat-card p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="icon-box">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="stat-number">{{ number_format($stats['total_amil']) }}</p>
            <p class="text-sm text-neutral-500 font-medium">Total Amil</p>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="glass-panel p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['total_jenis_zakat'] }}</p>
                    <p class="text-sm text-neutral-500">Jenis Zakat</p>
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
                    <p class="text-2xl font-bold text-primary-600">{{ $stats['total_kategori_mustahik'] }}</p>
                    <p class="text-sm text-neutral-500">Kategori Mustahik</p>
                </div>
            </div>
        </div>

        <div class="glass-panel p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-primary-600">
                        {{ $stats['harga_emas_terkini'] ? 'Rp ' . number_format($stats['harga_emas_terkini']->harga_emas_pergram, 0, ',', '.') : 'N/A' }}
                    </p>
                    <p class="text-sm text-neutral-500">Harga Emas/gram</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Masjid per Provinsi --}}
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-neutral-800 mb-4">Masjid per Provinsi (Top 5)</h3>
            <div style="height: 300px;">
                <canvas id="chartMasjidProvinsi"></canvas>
            </div>
        </div>

        {{-- Trend Registrasi --}}
        <div class="glass-panel p-6">
            <h3 class="text-lg font-bold text-neutral-800 mb-4">Trend Registrasi Masjid (6 Bulan)</h3>
            <div style="height: 300px;">
                <canvas id="chartTrendMasjid"></canvas>
            </div>
        </div>
    </div>

    {{-- Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Masjid Terbaru --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700">Masjid Terbaru</h3>
            </div>
            <div class="p-4">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 text-sm text-neutral-500">Nama</th>
                            <th class="text-left py-2 text-sm text-neutral-500">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($masjidTerbaru as $m)
                        <tr class="border-b last:border-0">
                            <td class="py-3">
                                <p class="font-semibold text-sm">{{ $m->nama }}</p>
                                <p class="text-xs text-neutral-400">{{ $m->kode_masjid }}</p>
                            </td>
                            <td class="py-3 text-sm text-neutral-600">{{ $m->kota_nama }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-8 text-center text-neutral-400">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pengguna Terbaru --}}
        <div class="glass-panel overflow-hidden">
            <div class="p-5 bg-primary-50 border-b border-primary-100">
                <h3 class="font-bold text-primary-700">Pengguna Terbaru</h3>
            </div>
            <div class="p-4">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2 text-sm text-neutral-500">Nama</th>
                            <th class="text-left py-2 text-sm text-neutral-500">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penggunaTerbaru as $p)
                        <tr class="border-b last:border-0">
                            <td class="py-3">
                                <p class="font-semibold text-sm">{{ $p->username }}</p>
                                <p class="text-xs text-neutral-400">{{ $p->email }}</p>
                            </td>
                            <td class="py-3">
                                <span class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-xs">{{ $p->peran }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-8 text-center text-neutral-400">Belum ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
        const now = new Date();
        document.getElementById('liveTime').textContent = now.toLocaleTimeString('id-ID');
    }, 1000);

    // Chart Colors
    const primaryColor = '#2d6936';
    const secondaryColor = '#7cb342';

    // Chart Masjid per Provinsi
    const masjidData = @json($masjidPerProvinsi);
    if (masjidData.length > 0) {
        new Chart(document.getElementById('chartMasjidProvinsi'), {
            type: 'bar',
            data: {
                labels: masjidData.map(i => i.nama),
                datasets: [{
                    label: 'Jumlah Masjid',
                    data: masjidData.map(i => i.jumlah),
                    backgroundColor: primaryColor + '80',
                    borderColor: primaryColor,
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }

    // Chart Trend
    const trendData = @json($trendMasjid);
    if (trendData.length > 0) {
        new Chart(document.getElementById('chartTrendMasjid'), {
            type: 'line',
            data: {
                labels: trendData.map(i => i.bulan),
                datasets: [{
                    label: 'Registrasi',
                    data: trendData.map(i => i.jumlah),
                    borderColor: primaryColor,
                    backgroundColor: primaryColor + '20',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>
@endpush