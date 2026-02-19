{{-- resources/views/amil/kas-harian/history.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Kas Harian')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('kas-harian.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-xl font-bold text-gray-900">Riwayat Kas Harian</h1>
            </div>
            <p class="text-sm text-gray-500 ml-7">Laporan kas harian per tanggal</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('kas-harian.export-excel', request()->all()) }}"
               class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    {{-- ===== FILTER ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-sm font-semibold text-gray-700">Filter</h2>
        </div>
        <div class="p-4 sm:p-6">
            <form method="GET" action="{{ route('kas-harian.history') }}" id="filter-form">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Status</label>
                        <select name="status" onchange="this.form.submit()"
                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all">
                            <option value="">Semua Status</option>
                            <option value="open"   {{ request('status') === 'open'   ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        Terapkan Filter
                    </button>
                    @if(request('start_date') || request('end_date') || request('status'))
                        <a href="{{ route('kas-harian.history') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition-all">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ===== CHART SALDO 30 HARI ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h2 class="text-sm sm:text-base font-semibold text-gray-900">Grafik Saldo 30 Hari Terakhir</h2>
        </div>
        <div class="p-4 sm:p-6">
            <canvas id="chartSaldo" height="100"></canvas>
        </div>
    </div>

    {{-- ===== TABEL RIWAYAT ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-sm sm:text-base font-semibold text-gray-900">Daftar Kas Harian</h2>
            <span class="text-xs text-gray-500">Total: {{ $kasHarian->total() }} data</span>
        </div>

        @if($kasHarian->count() > 0)
        {{-- Desktop Table --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Saldo Awal</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Penerimaan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Penyaluran</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Saldo Akhir</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Trx</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($kasHarian as $kas)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $kas->tanggal->translatedFormat('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $kas->tanggal->translatedFormat('l') }}</p>
                        </td>
                        <td class="px-4 py-3 text-right text-gray-600">{{ $kas->saldo_awal_formatted }}</td>
                        <td class="px-4 py-3 text-right font-medium text-green-700">{{ $kas->total_penerimaan_formatted }}</td>
                        <td class="px-4 py-3 text-right font-medium text-orange-700">{{ $kas->total_penyaluran_formatted }}</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900">{{ $kas->saldo_akhir_formatted }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="text-xs text-gray-500">
                                <span class="text-green-600 font-medium">+{{ $kas->jumlah_transaksi_masuk }}</span>
                                /
                                <span class="text-orange-600 font-medium">-{{ $kas->jumlah_transaksi_keluar }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">{!! $kas->status_badge !!}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('kas-harian.index', ['tanggal' => $kas->tanggal->format('Y-m-d')]) }}"
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium transition-colors">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden divide-y divide-gray-200">
            @foreach($kasHarian as $kas)
            <div class="p-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $kas->tanggal->translatedFormat('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $kas->tanggal->translatedFormat('l') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        {!! $kas->status_badge !!}
                        <a href="{{ route('kas-harian.index', ['tanggal' => $kas->tanggal->format('Y-m-d')]) }}"
                           class="text-xs text-blue-600 font-medium">Detail</a>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="bg-gray-50 rounded-lg p-2">
                        <p class="text-gray-400 mb-0.5">Saldo Awal</p>
                        <p class="font-medium text-gray-700">{{ $kas->saldo_awal_formatted }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-2">
                        <p class="text-gray-400 mb-0.5">Penerimaan</p>
                        <p class="font-medium text-green-700">{{ $kas->total_penerimaan_formatted }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-lg p-2">
                        <p class="text-gray-400 mb-0.5">Penyaluran</p>
                        <p class="font-medium text-orange-700">{{ $kas->total_penyaluran_formatted }}</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-2">
                        <p class="text-gray-400 mb-0.5">Saldo Akhir</p>
                        <p class="font-bold text-purple-700">{{ $kas->saldo_akhir_formatted }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($kasHarian->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
            {{ $kasHarian->links() }}
        </div>
        @endif

        @else
        <div class="p-8 sm:p-12 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-base font-medium text-gray-900 mb-2">Belum Ada Riwayat</h3>
            <p class="text-sm text-gray-500">
                @if(request('start_date') || request('end_date') || request('status'))
                    Tidak ada data yang sesuai dengan filter yang dipilih.
                @else
                    Belum ada kas harian yang tercatat.
                @endif
            </p>
            @if(request('start_date') || request('end_date') || request('status'))
                <a href="{{ route('kas-harian.history') }}"
                   class="inline-flex items-center mt-4 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                    Reset Filter
                </a>
            @endif
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chart30Hari);

    if (!chartData || chartData.length === 0) {
        document.getElementById('chartSaldo').closest('.p-4').innerHTML =
            '<p class="text-center text-sm text-gray-400 py-8">Belum ada data untuk ditampilkan.</p>';
        return;
    }

    const labels       = chartData.map(d => {
        const date = new Date(d.tanggal);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    const saldoAkhir   = chartData.map(d => parseFloat(d.saldo_akhir));
    const penerimaan   = chartData.map(d => parseFloat(d.total_penerimaan));
    const penyaluran   = chartData.map(d => parseFloat(d.total_penyaluran));

    const ctx = document.getElementById('chartSaldo').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Saldo Akhir',
                    data: saldoAkhir,
                    borderColor: '#7c3aed',
                    backgroundColor: 'rgba(124, 58, 237, 0.05)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#7c3aed',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Penerimaan',
                    data: penerimaan,
                    borderColor: '#16a34a',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#16a34a',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    borderDash: [4, 4],
                },
                {
                    label: 'Penyaluran',
                    data: penyaluran,
                    borderColor: '#ea580c',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#ea580c',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    borderDash: [4, 4],
                },
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 12 }, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': Rp ' +
                                ctx.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            if (val >= 1000000) return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
                            if (val >= 1000)    return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
                            return 'Rp ' + val;
                        },
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush