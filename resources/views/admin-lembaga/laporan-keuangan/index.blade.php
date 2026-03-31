@extends('layouts.app')

@section('title', 'Laporan Bulanan Keuangan Masjid')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden animate-slide-up">

        {{-- ── Header ─────────────────────────────────────────────────────── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900">Laporan Bulanan Keuangan</h2>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1">Monitor penerimaan dan penyaluran zakat per bulan</p>
                </div>
            </div>
        </div>

        <div class="p-4 sm:p-6">

            {{-- ── Profile Header (year selector + download) ──────────────── --}}
            <div class="pb-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-primary/10 flex items-center justify-center">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Tahun {{ $tahun }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Rekap keuangan 12 bulan</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                        {{-- Year filter --}}
                        <form method="GET" action="{{ route('laporan-keuangan.index') }}"
                              class="flex items-center gap-2">
                            <label for="tahun" class="text-xs sm:text-sm font-medium text-gray-700 whitespace-nowrap">Tahun:</label>
                            <select name="tahun" id="tahun" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </form>

                        {{-- Download annual --}}
                        <a href="{{ route('laporan-keuangan.download.tahunan.pdf', ['tahun' => $tahun]) }}"
                           class="inline-flex items-center px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors shadow-sm whitespace-nowrap">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Laporan Tahunan
                        </a>
                    </div>
                </div>
            </div>

            {{-- ── Chart Section ───────────────────────────────────────────── --}}
            <div class="mt-6">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                    Trend Penerimaan vs Penyaluran ({{ $tahun }})
                </h4>
                <div class="h-64">
                    <canvas id="laporanChart"></canvas>
                </div>
            </div>

            {{-- ── Table Section ───────────────────────────────────────────── --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">
                    Rekap Per Bulan
                </h4>

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penyaluran</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($laporanTahunan as $laporan)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $laporan->nama_bulan ?? \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F') }}
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $laporan->tahun }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-green-600">
                                        Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-red-500">
                                        Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-blue-600">
                                        Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if(isset($laporan->status_badge))
                                            {!! $laporan->status_badge !!}
                                        @elseif($laporan->status == 'draft')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                        @elseif($laporan->status == 'published')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Belum dibuat</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if($laporan->uuid)
                                                <a href="{{ route('laporan-keuangan.show', $laporan->uuid) }}"
                                                   class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-primary hover:text-primary-700 hover:bg-primary/5 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                            @endif

                                            @if(isset($laporan->can_generate) && $laporan->can_generate && !$laporan->uuid)
                                                <form action="{{ route('laporan-keuangan.generate', ['tahun' => $laporan->tahun, 'bulan' => $laporan->bulan]) }}"
                                                      method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                        Generate
                                                    </button>
                                                </form>
                                            @endif

                                            @if($laporan->uuid && $laporan->status == 'draft')
                                                <form action="{{ route('laporan-keuangan.publish', $laporan->uuid) }}"
                                                      method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg transition-colors">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Publish
                                                    </button>
                                                </form>
                                            @endif

                                            @if($laporan->uuid)
                                                <a href="{{ route('laporan-keuangan.download.pdf', $laporan->uuid) }}"
                                                   class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden space-y-3">
                    @foreach($laporanTahunan as $laporan)
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            {{-- Card Top --}}
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $laporan->nama_bulan ?? \Carbon\Carbon::createFromDate($laporan->tahun, $laporan->bulan, 1)->translatedFormat('F') }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $laporan->tahun }}</p>
                                </div>
                                <div>
                                    @if(isset($laporan->status_badge))
                                        {!! $laporan->status_badge !!}
                                    @elseif($laporan->status == 'draft')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Draft</span>
                                    @elseif($laporan->status == 'published')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Published</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Belum dibuat</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Card Body: Keuangan --}}
                            <div class="grid grid-cols-3 gap-2 mb-2">
                                <div class="text-center p-2 bg-gray-50 rounded-lg">
                                    <p class="text-xs text-gray-400 mb-0.5">Saldo Awal</p>
                                    <p class="text-xs font-semibold text-gray-700">Rp {{ number_format($laporan->saldo_awal, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-center p-2 bg-green-50 rounded-lg">
                                    <p class="text-xs text-green-500 mb-0.5">Penerimaan</p>
                                    <p class="text-xs font-semibold text-green-700">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-center p-2 bg-red-50 rounded-lg">
                                    <p class="text-xs text-red-400 mb-0.5">Penyaluran</p>
                                    <p class="text-xs font-semibold text-red-600">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="text-center p-2 bg-blue-50 rounded-lg mb-3">
                                <p class="text-xs text-blue-400 mb-0.5">Saldo Akhir</p>
                                <p class="text-sm font-bold text-blue-700">Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}</p>
                            </div>

                            {{-- Card Footer: Actions --}}
                            <div class="flex flex-wrap gap-1.5">
                                @if($laporan->uuid)
                                    <a href="{{ route('laporan-keuangan.show', $laporan->uuid) }}"
                                       class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Detail →
                                    </a>
                                @endif

                                @if(isset($laporan->can_generate) && $laporan->can_generate && !$laporan->uuid)
                                    <form action="{{ route('laporan-keuangan.generate', ['tahun' => $laporan->tahun, 'bulan' => $laporan->bulan]) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Generate
                                        </button>
                                    </form>
                                @endif

                                @if($laporan->uuid && $laporan->status == 'draft')
                                    <form action="{{ route('laporan-keuangan.publish', $laporan->uuid) }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Publish
                                        </button>
                                    </form>
                                @endif

                                @if($laporan->uuid)
                                    <a href="{{ route('laporan-keuangan.download.pdf', $laporan->uuid) }}"
                                       class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-purple-600 hover:bg-purple-50 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>{{-- /p-4 sm:p-6 --}}

        {{-- ── Footer ──────────────────────────────────────────────────────── --}}
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-xs text-gray-400">
                    Menampilkan data laporan tahun {{ $tahun }}
                </p>
            </div>
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