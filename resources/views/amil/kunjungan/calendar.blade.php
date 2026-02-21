{{-- resources/views/amil/kunjungan/calendar.blade.php --}}
@extends('layouts.app')
@section('title', 'Kalender Kunjungan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.css"/>
<style>
    .fc-event { cursor: pointer; }
    .fc-event-title { font-weight: 500; font-size: 0.75rem; }
    .fc-toolbar-title { font-size: 1rem !important; font-weight: 600; }
    .fc-button { font-size: 0.75rem !important; padding: 0.25rem 0.6rem !important; }
    .fc-button-primary { background-color: #2563eb !important; border-color: #2563eb !important; }
    .fc-button-primary:hover { background-color: #1d4ed8 !important; }
    .fc-button-active { background-color: #1e40af !important; }
    .fc-day-today { background: #eff6ff !important; }
    .fc-daygrid-day-number { font-size: 0.8rem; }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Kalender Kunjungan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola jadwal kunjungan mustahik Anda</p>
        </div>
        <a href="{{ route('amil.kunjungan.create') }}"
            class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jadwal
        </a>
    </div>

    {{-- Statistik Bulan Ini --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 font-medium">Total Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-4">
            <p class="text-xs text-blue-600 font-medium">Direncanakan</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['direncanakan'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4">
            <p class="text-xs text-green-600 font-medium">Selesai</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    {{-- Filter & Toggle --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <div class="flex items-center gap-2 flex-wrap flex-1">
                <span class="text-xs font-medium text-gray-500">Filter:</span>
                <select id="filter-tujuan"
                    class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">Semua Tujuan</option>
                    @foreach($tujuanOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <select id="filter-status"
                    class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                <button id="btn-reset-filter"
                    class="text-xs text-gray-500 hover:text-gray-700 underline">Reset</button>
            </div>

            {{-- Legend --}}
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-blue-500"></span>Direncanakan</span>
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>Selesai</span>
                <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full bg-red-500"></span>Dibatalkan</span>
            </div>

            {{-- Toggle View --}}
            <div class="flex rounded-lg overflow-hidden border border-gray-200">
                <button id="btn-calendar-view" onclick="setView('calendar')"
                    class="px-3 py-1.5 text-xs font-medium bg-primary text-white transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </button>
                <button id="btn-list-view" onclick="setView('list')"
                    class="px-3 py-1.5 text-xs font-medium bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Kalender --}}
    <div id="view-calendar" class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div id="fullcalendar"></div>
    </div>

    {{-- List View --}}
    <div id="view-list" class="hidden bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div id="list-loading" class="p-8 text-center text-gray-400 text-sm">
            <svg class="w-5 h-5 animate-spin mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>Memuat data...
        </div>
        <div id="list-table" class="hidden">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Daftar Kunjungan</h3>
                <input type="month" id="filter-bulan"
                    value="{{ now()->format('Y-m') }}"
                    class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Mustahik</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Tujuan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Waktu</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody id="list-tbody" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <div id="list-pagination" class="px-4 py-3 border-t border-gray-200 flex justify-center gap-2 text-xs"></div>
        </div>
    </div>
</div>

{{-- Event Detail Modal --}}
<div id="event-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm">
        <div class="p-5">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900" id="modal-title">-</h3>
                    <p class="text-xs text-gray-500 mt-0.5" id="modal-tujuan">-</p>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="space-y-2 mb-5">
                <p class="text-sm text-gray-700" id="modal-tanggal"></p>
                <div id="modal-status-badge"></div>
            </div>
            <div class="flex gap-2">
                <a id="modal-detail-btn" href="#"
                    class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium bg-primary text-white rounded-xl hover:bg-primary-600 transition-colors">
                    Lihat Detail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.js"></script>
<script>
// ── State ─────────────────────────────────────────────────────────────────
let calendar;
let currentView = 'calendar';
let filterTujuan = '';
let filterStatus = '';

// ── FullCalendar ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('fullcalendar');
    calendar = new FullCalendar.Calendar(el, {
        locale: 'id',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'List' },
        height: 'auto',
        events: fetchEvents,

        // Klik event → modal
        eventClick: function (info) {
            info.jsEvent.preventDefault();
            openModal(info.event);
        },

        // Klik tanggal kosong → create
        dateClick: function (info) {
            const url = new URL('{{ route("amil.kunjungan.create") }}');
            url.searchParams.set('tanggal', info.dateStr);
            window.location.href = url.toString();
        },
    });
    calendar.render();

    // Filter listener
    document.getElementById('filter-tujuan').addEventListener('change', function () {
        filterTujuan = this.value;
        calendar.refetchEvents();
        if (currentView === 'list') loadList();
    });
    document.getElementById('filter-status').addEventListener('change', function () {
        filterStatus = this.value;
        calendar.refetchEvents();
        if (currentView === 'list') loadList();
    });
    document.getElementById('btn-reset-filter').addEventListener('click', function () {
        filterTujuan = '';
        filterStatus = '';
        document.getElementById('filter-tujuan').value = '';
        document.getElementById('filter-status').value = '';
        calendar.refetchEvents();
        if (currentView === 'list') loadList();
    });
    document.getElementById('filter-bulan').addEventListener('change', loadList);
});

function fetchEvents(info, successCallback, failureCallback) {
    const params = new URLSearchParams({
        start: info.startStr,
        end: info.endStr,
        tujuan: filterTujuan,
        status: filterStatus,
    });
    fetch(`{{ route('amil.kunjungan.events') }}?${params}`)
        .then(r => r.json())
        .then(successCallback)
        .catch(failureCallback);
}

// ── List View ─────────────────────────────────────────────────────────────
function setView(view) {
    currentView = view;
    document.getElementById('view-calendar').classList.toggle('hidden', view === 'list');
    document.getElementById('view-list').classList.toggle('hidden', view === 'calendar');
    document.getElementById('btn-calendar-view').className =
        `px-3 py-1.5 text-xs font-medium transition-colors ${view === 'calendar' ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:bg-gray-50'}`;
    document.getElementById('btn-list-view').className =
        `px-3 py-1.5 text-xs font-medium transition-colors ${view === 'list' ? 'bg-primary text-white' : 'bg-white text-gray-600 hover:bg-gray-50'}`;
    if (view === 'list') loadList();
}

function loadList(page = 1) {
    const bulan  = document.getElementById('filter-bulan').value;
    const params = new URLSearchParams({ bulan, tujuan: filterTujuan, status: filterStatus, page });

    document.getElementById('list-loading').classList.remove('hidden');
    document.getElementById('list-table').classList.add('hidden');

    fetch(`{{ route('amil.kunjungan.list-data') }}?${params}`)
        .then(r => r.json())
        .then(data => {
            renderListTable(data.data);
            renderPagination(data);
            document.getElementById('list-loading').classList.add('hidden');
            document.getElementById('list-table').classList.remove('hidden');
        });
}

const tujuanColors = { verifikasi: 'indigo', penyaluran: 'green', monitoring: 'yellow', lainnya: 'gray' };
const statusColors = { direncanakan: 'blue', selesai: 'green', dibatalkan: 'red' };

function renderListTable(rows) {
    const tbody = document.getElementById('list-tbody');
    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada kunjungan</td></tr>';
        return;
    }
    tbody.innerHTML = rows.map(k => {
        const tColor = tujuanColors[k.tujuan] || 'gray';
        const sColor = statusColors[k.status] || 'gray';
        const waktu  = k.waktu_mulai ? k.waktu_mulai.slice(0,5) + (k.waktu_selesai ? ' – ' + k.waktu_selesai.slice(0,5) : '') : '-';
        const tgl    = new Date(k.tanggal_kunjungan).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' });
        return `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">${tgl}</td>
            <td class="px-4 py-3">
                <p class="text-sm font-medium text-gray-900">${k.mustahik?.nama_lengkap ?? '-'}</p>
                <p class="text-xs text-gray-400">${k.mustahik?.alamat ?? ''}</p>
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-${tColor}-100 text-${tColor}-800">${k.tujuan_label ?? k.tujuan}</span>
            </td>
            <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-${sColor}-100 text-${sColor}-800">${k.status}</span>
            </td>
            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">${waktu}</td>
            <td class="px-4 py-3 text-right">
                <a href="/kunjungan/${k.uuid}" class="text-xs text-primary font-medium hover:underline">Detail</a>
            </td>
        </tr>`;
    }).join('');
}

function renderPagination(data) {
    const el = document.getElementById('list-pagination');
    if (data.last_page <= 1) { el.innerHTML = ''; return; }
    let html = '';
    for (let p = 1; p <= data.last_page; p++) {
        html += `<button onclick="loadList(${p})"
            class="px-2.5 py-1 rounded text-xs border ${p === data.current_page ? 'bg-primary text-white border-primary' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'}">${p}</button>`;
    }
    el.innerHTML = html;
}

// ── Modal ─────────────────────────────────────────────────────────────────
function openModal(event) {
    const props   = event.extendedProps;
    const tgl     = event.start ? event.start.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' }) : '-';
    const sColor  = { direncanakan: 'blue', selesai: 'green', dibatalkan: 'red' }[props.status] || 'gray';
    const sLabel  = { direncanakan: 'Direncanakan', selesai: 'Selesai', dibatalkan: 'Dibatalkan' }[props.status] || props.status;

    document.getElementById('modal-title').textContent  = event.title;
    document.getElementById('modal-tujuan').textContent = 'Tujuan: ' + (props.tujuan ?? '-');
    document.getElementById('modal-tanggal').textContent = tgl;
    document.getElementById('modal-status-badge').innerHTML =
        `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${sColor}-100 text-${sColor}-800">${sLabel}</span>`;
    document.getElementById('modal-detail-btn').href = props.url;

    const modal = document.getElementById('event-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeModal() {
    const modal = document.getElementById('event-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('event-modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush