@extends('layouts.app')

@section('title', 'Detail Laporan Bulanan')

@section('content')
<div class="space-y-4 sm:space-y-6">
    {{-- Header --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Detail Laporan {{ $laporan->periode }}</h2>
                    <div class="flex items-center gap-2 mt-2">
                        {!! $laporan->status_badge !!}
                        <span class="text-sm text-gray-500">
                            Periode: {{ $laporan->periode_mulai->format('d M Y') }} - {{ $laporan->periode_selesai->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($laporan->can_publish)
                        <form action="{{ route('laporan-keuangan.publish', $laporan->uuid) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Publish Laporan
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('laporan-keuangan.download.pdf', $laporan->uuid) }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </a>
                    
                    @if($laporan->status === 'draft')
                        <a href="#" 
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-sm text-blue-600 mb-1">Saldo Awal</p>
                    <p class="text-2xl font-semibold text-blue-900">Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-sm text-green-600 mb-1">Total Penerimaan</p>
                    <p class="text-2xl font-semibold text-green-900">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $laporan->jumlah_muzakki }} Muzakki • {{ $laporan->jumlah_transaksi_masuk }} Transaksi</p>
                </div>
                <div class="bg-red-50 p-4 rounded-lg">
                    <p class="text-sm text-red-600 mb-1">Total Penyaluran</p>
                    <p class="text-2xl font-semibold text-red-900">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</p>
                    <p class="text-xs text-red-600 mt-1">{{ $laporan->jumlah_mustahik }} Mustahik • {{ $laporan->jumlah_transaksi_keluar }} Transaksi</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <p class="text-sm text-purple-600 mb-1">Saldo Akhir</p>
                    <p class="text-2xl font-semibold text-purple-900">Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Penerimaan per Jenis Zakat --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Breakdown Penerimaan per Jenis Zakat</h3>
                    <div class="h-64">
                        <canvas id="penerimaanChart"></canvas>
                    </div>
                </div>
                
                {{-- Penyaluran per Kategori Mustahik --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Breakdown Penyaluran per Kategori Mustahik</h3>
                    <div class="h-64">
                        <canvas id="penyaluranChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Transaksi Penerimaan --}}
        <div class="p-4 sm:p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Detail Transaksi Penerimaan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Zakat</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailPenerimaan as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['jenis_zakat'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['count'] }}</td>
                                <td class="px-4 py-3 text-sm text-green-600 font-medium">
                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $laporan->total_penerimaan > 0 ? number_format(($item['jumlah'] / $laporan->total_penerimaan) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($detailPenerimaan))
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data penerimaan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Detail Transaksi Penyaluran --}}
        <div class="p-4 sm:p-6 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Detail Transaksi Penyaluran</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Mustahik</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Mustahik</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detailPenyaluran as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['kategori'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item['count'] }}</td>
                                <td class="px-4 py-3 text-sm text-red-600 font-medium">
                                    Rp {{ number_format($item['jumlah'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $laporan->total_penyaluran > 0 ? number_format(($item['jumlah'] / $laporan->total_penyaluran) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($detailPenyaluran))
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data penyaluran
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('laporan-keuangan.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Laporan
                </a>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>Dibuat oleh: {{ $laporan->creator->nama_lengkap ?? 'System' }}</span>
                    <span>•</span>
                    <span>Terakhir diupdate: {{ $laporan->updated_at->format('d M Y, H:i') }}</span>
                    @if($laporan->published_at)
                        <span>•</span>
                        <span>Dipublikasi: {{ $laporan->published_at->format('d M Y, H:i') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Penerimaan Chart
    const penerimaanCtx = document.getElementById('penerimaanChart').getContext('2d');
    const penerimaanData = @json($chartPenerimaan);
    
    new Chart(penerimaanCtx, {
        type: 'pie',
        data: {
            labels: penerimaanData.labels,
            datasets: [{
                data: penerimaanData.data,
                backgroundColor: penerimaanData.backgroundColor,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Penyaluran Chart
    const penyaluranCtx = document.getElementById('penyaluranChart').getContext('2d');
    const penyaluranData = @json($chartPenyaluran);
    
    new Chart(penyaluranCtx, {
        type: 'pie',
        data: {
            labels: penyaluranData.labels,
            datasets: [{
                data: penyaluranData.data,
                backgroundColor: penyaluranData.backgroundColor,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush