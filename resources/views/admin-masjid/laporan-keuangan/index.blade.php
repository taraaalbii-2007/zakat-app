@extends('layouts.app')

@section('title', 'Laporan Bulanan Keuangan Masjid')

@section('content')
<div class="space-y-4 sm:space-y-6">
    {{-- Filter Section --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Laporan Bulanan Keuangan</h2>
                    <p class="text-sm text-gray-500 mt-1">Monitor penerimaan dan penyaluran zakat per bulan</p>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('laporan-keuangan.index') }}" class="flex items-center gap-2">
                        <label for="tahun" class="text-sm font-medium text-gray-700">Tahun:</label>
                        <select name="tahun" id="tahun" onchange="this.form.submit()" 
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('laporan-keuangan.download.tahunan.pdf', ['tahun' => $tahun]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Laporan Tahunan
                    </a>
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Trend Penerimaan vs Penyaluran ({{ $tahun }})</h3>
            <div class="h-64">
                <canvas id="laporanChart"></canvas>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyaluran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($laporanTahunan as $laporan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $laporan->nama_bulan ?? \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F') }}</div>
                                <div class="text-xs text-gray-500">{{ $laporan->tahun }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">
                                Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-medium">
                                Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($laporan->status_badge))
                                    {!! $laporan->status_badge !!}
                                @else
                                    @if($laporan->status == 'draft')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                    @elseif($laporan->status == 'published')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Belum dibuat</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                @if($laporan->uuid)
                                    <a href="{{ route('laporan-keuangan.show', $laporan->uuid) }}" 
                                       class="text-primary hover:text-primary-600">View Detail</a>
                                @endif
                                
                                @if(isset($laporan->can_generate) && $laporan->can_generate && !$laporan->uuid)
                                    <form action="{{ route('laporan-keuangan.generate', ['tahun' => $laporan->tahun, 'bulan' => $laporan->bulan]) }}" 
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-800">Generate</button>
                                    </form>
                                @endif
                                
                                @if($laporan->uuid && $laporan->status == 'draft')
                                    <form action="{{ route('laporan-keuangan.publish', $laporan->uuid) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800">Publish</button>
                                    </form>
                                @endif

                                @if($laporan->uuid)
                                    <a href="{{ route('laporan-keuangan.download.pdf', $laporan->uuid) }}" 
                                       class="text-purple-600 hover:text-purple-800">Download</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('laporanChart').getContext('2d');
    
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Penerimaan',
                    data: chartData.penerimaan,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Penyaluran',
                    data: chartData.penyaluran,
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush