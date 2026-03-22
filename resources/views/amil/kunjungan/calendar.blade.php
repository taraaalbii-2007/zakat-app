{{-- resources/views/amil/kunjungan/calendar.blade.php --}}
@extends('layouts.app')
@section('title', 'Kunjungan Mustahik')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.css"/>
<style>
    .fc-event               { cursor: pointer; border: none !important; box-shadow: none !important; }
    .fc-event-title         { font-weight: 600; font-size: 0.72rem; }
    .fc-toolbar-title       { font-size: 0.95rem !important; font-weight: 700; color: #111827; }

    /* Tombol lebih lega, tidak mepet */
    .fc-button              { font-size: 0.78rem !important; padding: 0.38rem 0.9rem !important; border-radius: 0.5rem !important; font-weight: 600 !important; }
    .fc-button-primary      { background-color: #2563eb !important; border-color: #2563eb !important; }
    .fc-button-primary:hover{ background-color: #1d4ed8 !important; }
    .fc-button-active       { background-color: #1e40af !important; }

    /* Pisah tombol di grup — tidak menempel */
    .fc .fc-button-group    { gap: 6px !important; display: inline-flex !important; }
    .fc .fc-button-group > .fc-button { border-radius: 0.5rem !important; margin: 0 !important; }

    /* "Hari Ini" punya jarak dari grup panah */
    .fc .fc-today-button    { margin-left: 8px !important; }

    .fc-day-today           { background: #eff6ff !important; }
    .fc-daygrid-day-number  { font-size: 0.75rem; color: #6b7280; }
    .fc-col-header-cell-cushion { font-size: 0.72rem; font-weight: 700; color: #9ca3af; }
    .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
        background: #2563eb; color: #fff;
        width: 22px; height: 22px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
    }

    /* Dropdown filter panel */
    #filter-panel { display: none; }
    #filter-panel.open { display: flex; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up { animation: slideUp .2s ease both; }
</style>
@endpush

@section('content')
<div class="space-y-4 sm:space-y-5">
    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 rounded-xl animate-slide-up">
        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="flex-1 text-sm font-medium text-green-800">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl animate-slide-up">
        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="flex-1 text-sm font-medium text-red-800">{{ session('error') }}</p>
    </div>
    @endif
    @if(session('info'))
    <div class="flex items-center gap-3 px-4 py-3 bg-blue-50 border border-blue-200 rounded-xl animate-slide-up">
        <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="flex-1 text-sm font-medium text-blue-800">{{ session('info') }}</p>
    </div>
    @endif

    {{-- ===== STATISTIK CARDS ===== --}}
    <div class="grid grid-cols-3 gap-4 animate-slide-up">

        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Bulan Ini</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-0.5">{{ $stats['total'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">kunjungan</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-blue-200 p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-blue-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-blue-600">Direncanakan</p>
                <p class="text-2xl sm:text-3xl font-bold text-blue-700 mt-0.5">{{ $stats['direncanakan'] }}</p>
                <p class="text-xs text-blue-400 mt-0.5">jadwal aktif</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-green-200 p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-green-50 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-green-600">Selesai</p>
                <p class="text-2xl sm:text-3xl font-bold text-green-700 mt-0.5">{{ $stats['selesai'] }}</p>
                <p class="text-xs text-green-400 mt-0.5">kunjungan selesai</p>
            </div>
        </div>

    </div>

    {{-- ===== MAIN CARD ===== --}}
    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-gray-900">Kunjungan Mustahik</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                {{-- Date picker --}}
                <form method="GET" action="{{ route('amil.kunjungan.index') }}" class="flex items-center">
                    <input type="date"
                        name="tanggal"
                        value="{{ request('tanggal', now()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        onchange="this.form.submit()"
                        class="px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </form>

                {{-- Filter button (buka dropdown panel) --}}
                <div class="relative">
                    <button type="button" id="btn-filter" onclick="toggleFilterPanel()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                        </svg>
                        Filter
                        <span id="filter-count-badge" class="hidden px-1.5 py-0.5 text-xs font-semibold bg-blue-600 text-white rounded-full leading-none">0</span>
                    </button>
                </div>

                {{-- Catatan --}}
                <button type="button" onclick="openCatatanModal()"
                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Catatan
                </button>

                {{-- Tambah Jadwal --}}
                <a href="{{ route('amil.kunjungan.create') }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Jadwal
                </a>
            </div>
        </div>

        {{-- Tabs + View Toggle --}}
        <div class="flex items-center justify-between px-5 sm:px-6 border-b border-gray-200">
            <nav class="flex gap-0" id="kunjungan-tabs">
                <button type="button" onclick="switchTab('direncanakan')" id="tab-direncanakan"
                    class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-all">
                    Direncanakan
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700">{{ $stats['direncanakan'] }}</span>
                </button>
                <button type="button" onclick="switchTab('selesai')" id="tab-selesai"
                    class="tab-btn px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition-all">
                    Selesai
                    <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700">{{ $stats['selesai'] }}</span>
                </button>
            </nav>
            <div class="flex rounded-lg overflow-hidden border border-gray-200 flex-shrink-0 my-2">
                <button id="btn-calendar-view" onclick="setView('calendar')"
                    class="px-3 py-1.5 text-xs font-medium bg-blue-600 text-white transition-colors" title="Kalender">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </button>
                <button id="btn-list-view" onclick="setView('list')"
                    class="px-3 py-1.5 text-xs font-medium bg-white text-gray-600 hover:bg-gray-50 border-l border-gray-200 transition-colors" title="List">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Filter dropdown panel (muncul saat tombol Filter diklik) --}}
        <div id="filter-panel"
            class="px-5 sm:px-6 py-3 border-b border-gray-100 bg-blue-50/40 flex-wrap items-center gap-3">
            <span class="text-xs font-medium text-gray-500">Filter:</span>
            <select id="filter-tujuan"
                class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                <option value="">Semua Tujuan</option>
                @foreach($tujuanOptions as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
            <select id="filter-status"
                class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                @endforeach
            </select>
            <button id="btn-reset-filter" class="text-xs text-gray-400 hover:text-red-500 underline transition-colors">Reset</button>
            <div class="flex items-center gap-3 text-xs text-gray-500 ml-auto">
                <span class="flex items-center gap-1.5"><span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span>Direncanakan</span>
                <span class="flex items-center gap-1.5"><span class="inline-block w-2.5 h-2.5 rounded-full bg-green-500"></span>Selesai</span>
                <span class="flex items-center gap-1.5"><span class="inline-block w-2.5 h-2.5 rounded-full bg-red-500"></span>Dibatalkan</span>
            </div>
        </div>

        {{-- View: Kalender --}}
        <div id="view-calendar" class="p-4 sm:p-5">
            <div id="fullcalendar"></div>
        </div>

        {{-- View: List --}}
        <div id="view-list" class="hidden">
            <div class="px-5 sm:px-6 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-900">Daftar Kunjungan</h3>
                <input type="month" id="filter-bulan" value="{{ now()->format('Y-m') }}"
                    class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
            <div id="list-loading" class="py-10 text-center">
                <svg class="w-5 h-5 animate-spin mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                </svg>
                <p class="text-sm text-gray-400">Memuat data...</p>
            </div>
            <div id="list-table" class="hidden">
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Mustahik</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 w-20"></th>
                            </tr>
                        </thead>
                        <tbody id="list-tbody" class="bg-white divide-y divide-gray-50"></tbody>
                    </table>
                </div>
                <div id="list-mobile" class="md:hidden divide-y divide-gray-100"></div>
                <div id="list-pagination"
                    class="px-5 sm:px-6 py-3 border-t border-gray-100 flex items-center justify-center gap-2 flex-wrap bg-gray-50/50"></div>
            </div>
        </div>

    </div>

</div>

{{-- ===== MODAL: CATATAN ===== --}}
<div id="catatan-modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-900">Catatan Kunjungan</h3>
                <p class="text-xs text-gray-500">Tambahkan catatan untuk hari ini</p>
            </div>
        </div>
        <form method="POST" action="#">
            @csrf
            <textarea name="catatan" rows="4" placeholder="Tulis catatan (opsional)..."
                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none mb-4"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('catatan-modal')"
                    class="px-4 py-2.5 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-sm font-semibold text-white transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL: EVENT DETAIL ===== --}}
<div id="event-modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-[10000] flex items-center justify-center p-4">
    <div class="p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="text-base font-semibold text-gray-900" id="modal-title">-</h3>
                <p class="text-xs text-gray-500 mt-0.5" id="modal-tujuan">-</p>
            </div>
            <button onclick="closeModal('event-modal')"
                class="text-gray-400 hover:text-gray-600 transition-colors ml-3 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="bg-gray-50 rounded-xl p-3 mb-5 space-y-2">
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-semibold text-gray-800" id="modal-tanggal">-</span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-500">Waktu</span>
                <span class="font-semibold text-gray-800" id="modal-waktu">-</span>
            </div>
            <div class="flex justify-between items-center text-xs pt-1 border-t border-gray-200">
                <span class="text-gray-500">Status</span>
                <span id="modal-status-badge"></span>
            </div>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="closeModal('event-modal')"
                class="flex-1 inline-flex items-center justify-center px-3 py-2.5 text-sm font-medium border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 rounded-xl transition-colors">
                Tutup
            </button>
            <a id="modal-detail-btn" href="#"
                class="flex-1 inline-flex items-center justify-center px-3 py-2.5 text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors">
                Lihat Detail
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.10/index.global.min.js"></script>
<script>
let calendar, currentView = 'calendar', filterTujuan = '', filterStatus = '', activeTab = 'direncanakan';

const STATUS_BADGE = {
    direncanakan: '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Direncanakan</span>',
    selesai:      '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Selesai</span>',
    dibatalkan:   '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Dibatalkan</span>',
};
const TUJUAN_BADGE = {
    verifikasi: '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">Verifikasi</span>',
    penyaluran: '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Penyaluran</span>',
    monitoring:  '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Monitoring</span>',
    lainnya:     '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">Lainnya</span>',
};

document.addEventListener('DOMContentLoaded', function () {
    calendar = new FullCalendar.Calendar(document.getElementById('fullcalendar'), {
        locale: 'id',
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listWeek' },
        buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'Minggu' },
        height: 'auto',
        events: fetchEvents,
        eventClick: function (info) { info.jsEvent.preventDefault(); openEventModal(info.event); },
        dateClick: function (info) {
            const url = new URL('{{ route("amil.kunjungan.create") }}');
            url.searchParams.set('tanggal', info.dateStr);
            window.location.href = url.toString();
        },
    });
    calendar.render();

    document.getElementById('filter-tujuan').addEventListener('change', function () {
        filterTujuan = this.value;
        updateFilterBadge();
        calendar.refetchEvents();
        if (currentView === 'list') loadList();
    });
    document.getElementById('filter-status').addEventListener('change', function () {
        filterStatus = this.value;
        updateFilterBadge();
        calendar.refetchEvents();
        if (currentView === 'list') loadList();
    });
    document.getElementById('btn-reset-filter').addEventListener('click', function () {
        filterTujuan = ''; filterStatus = '';
        document.getElementById('filter-tujuan').value = '';
        document.getElementById('filter-status').value = '';
        updateFilterBadge();
        calendar.refetchEvents();
        if (currentView === 'list') loadList();
    });
    document.getElementById('filter-bulan').addEventListener('change', loadList);

    ['catatan-modal', 'event-modal'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', function (e) { if (e.target === this) closeModal(id); });
    });
});

function fetchEvents(info, successCallback, failureCallback) {
    const params = new URLSearchParams({ start: info.startStr, end: info.endStr, tujuan: filterTujuan, status: filterStatus, tab: activeTab });
    fetch(`{{ route('amil.kunjungan.events') }}?${params}`).then(r => r.json()).then(successCallback).catch(failureCallback);
}

function switchTab(tab) {
    activeTab = tab;
    ['direncanakan', 'selesai'].forEach(function (t) {
        const btn = document.getElementById('tab-' + t);
        if (t === tab) { btn.classList.add('border-blue-600', 'text-blue-600'); btn.classList.remove('border-transparent', 'text-gray-500'); }
        else { btn.classList.remove('border-blue-600', 'text-blue-600'); btn.classList.add('border-transparent', 'text-gray-500'); }
    });
    calendar.refetchEvents();
    if (currentView === 'list') loadList();
}

function setView(view) {
    currentView = view;
    const isCal = view === 'calendar';
    document.getElementById('view-calendar').classList.toggle('hidden', !isCal);
    document.getElementById('view-list').classList.toggle('hidden', isCal);
    const c = `px-3 py-1.5 text-xs font-medium transition-colors`;
    document.getElementById('btn-calendar-view').className = c + (isCal  ? ' bg-blue-600 text-white' : ' bg-white text-gray-600 hover:bg-gray-50');
    document.getElementById('btn-list-view').className     = c + ' border-l border-gray-200' + (!isCal ? ' bg-blue-600 text-white' : ' bg-white text-gray-600 hover:bg-gray-50');
    if (view === 'list') loadList();
}

function loadList(page = 1) {
    const bulan = document.getElementById('filter-bulan').value;
    const params = new URLSearchParams({ bulan, tujuan: filterTujuan, status: filterStatus, tab: activeTab, page });
    document.getElementById('list-loading').classList.remove('hidden');
    document.getElementById('list-table').classList.add('hidden');
    fetch(`{{ route('amil.kunjungan.list-data') }}?${params}`)
        .then(r => r.json())
        .then(data => {
            renderListDesktop(data.data);
            renderListMobile(data.data);
            renderPagination(data);
            document.getElementById('list-loading').classList.add('hidden');
            document.getElementById('list-table').classList.remove('hidden');
        }).catch(() => { document.getElementById('list-loading').classList.add('hidden'); });
}

function renderListDesktop(rows) {
    const tbody = document.getElementById('list-tbody');
    if (!rows || !rows.length) {
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-14 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-3">
                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
            <p class="text-sm font-semibold text-gray-800">Tidak Ada Kunjungan</p>
            <p class="text-xs text-gray-400 mt-1">Belum ada kunjungan pada bulan ini</p></td></tr>`;
        return;
    }
    tbody.innerHTML = rows.map(k => {
        const tgl = new Date(k.tanggal_kunjungan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        const waktu = k.waktu_mulai ? k.waktu_mulai.slice(0,5) + (k.waktu_selesai ? ' – ' + k.waktu_selesai.slice(0,5) : '') : '-';
        const sb = STATUS_BADGE[k.status]  ?? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">${k.status}</span>`;
        const tb = TUJUAN_BADGE[k.tujuan] ?? `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">${k.tujuan}</span>`;
        return `<tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">${tgl}</td>
            <td class="px-6 py-4"><p class="text-sm font-semibold text-gray-900">${k.mustahik?.nama_lengkap ?? '-'}</p><p class="text-xs text-gray-400 mt-0.5">${k.mustahik?.alamat ?? ''}</p></td>
            <td class="px-6 py-4">${tb}</td>
            <td class="px-6 py-4">${sb}</td>
            <td class="px-6 py-4 text-xs text-gray-500 whitespace-nowrap">${waktu}</td>
            <td class="px-6 py-4 text-center"><a href="/kunjungan/${k.uuid}" class="inline-flex items-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition-all">Detail</a></td>
        </tr>`;
    }).join('');
}

function renderListMobile(rows) {
    const el = document.getElementById('list-mobile');
    if (!rows || !rows.length) { el.innerHTML = ''; return; }
    el.innerHTML = rows.map(k => {
        const tgl = new Date(k.tanggal_kunjungan).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        const waktu = k.waktu_mulai ? k.waktu_mulai.slice(0,5) + (k.waktu_selesai ? ' – ' + k.waktu_selesai.slice(0,5) : '') : '-';
        return `<div class="p-4 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">${k.mustahik?.nama_lengkap ?? '-'}</p>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                        <span class="text-xs text-gray-500">${tgl}</span>
                        <span class="text-gray-300">·</span>
                        <span class="text-xs text-gray-500">${waktu}</span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-1.5 flex-wrap">${TUJUAN_BADGE[k.tujuan]??''} ${STATUS_BADGE[k.status]??''}</div>
                </div>
                <a href="/kunjungan/${k.uuid}" class="flex-shrink-0 inline-flex items-center px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-semibold rounded-lg transition-all">Detail</a>
            </div>
        </div>`;
    }).join('');
}

function renderPagination(data) {
    const el = document.getElementById('list-pagination');
    if (!data || data.last_page <= 1) { el.innerHTML = ''; return; }
    let html = '';
    for (let p = 1; p <= data.last_page; p++) {
        const a = p === data.current_page;
        html += `<button onclick="loadList(${p})" class="px-3 py-1.5 rounded-lg text-xs font-semibold border transition-all ${a ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50'}">${p}</button>`;
    }
    el.innerHTML = html;
}

function openEventModal(event) {
    const props = event.extendedProps;
    const tgl = event.start ? event.start.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' }) : '-';
    const waktu = props.waktu_mulai ? props.waktu_mulai.slice(0,5) + (props.waktu_selesai ? ' – '+props.waktu_selesai.slice(0,5) : '') : '-';
    document.getElementById('modal-title').textContent = event.title;
    document.getElementById('modal-tujuan').textContent = 'Tujuan: ' + (props.tujuan_label ?? props.tujuan ?? '-');
    document.getElementById('modal-tanggal').textContent = tgl;
    document.getElementById('modal-waktu').textContent = waktu;
    document.getElementById('modal-status-badge').innerHTML = STATUS_BADGE[props.status] ?? props.status;
    document.getElementById('modal-detail-btn').href = props.url ?? '#';
    document.getElementById('event-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function toggleFilterPanel() {
    const panel = document.getElementById('filter-panel');
    const btn   = document.getElementById('btn-filter');
    panel.classList.toggle('open');
    btn.classList.toggle('bg-blue-100');
    btn.classList.toggle('text-blue-700');
    btn.classList.toggle('bg-gray-100');
    btn.classList.toggle('text-gray-700');
}

function updateFilterBadge() {
    const count = (filterTujuan ? 1 : 0) + (filterStatus ? 1 : 0);
    const badge = document.getElementById('filter-count-badge');
    if (count > 0) {
        badge.textContent = count;
        badge.classList.remove('hidden');
        document.getElementById('btn-filter').classList.add('bg-blue-100', 'text-blue-700');
        document.getElementById('btn-filter').classList.remove('bg-gray-100', 'text-gray-700');
    } else {
        badge.classList.add('hidden');
        document.getElementById('btn-filter').classList.remove('bg-blue-100', 'text-blue-700');
        document.getElementById('btn-filter').classList.add('bg-gray-100', 'text-gray-700');
    }
}

function openCatatanModal() { document.getElementById('catatan-modal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.style.overflow = ''; }
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeModal('catatan-modal'); closeModal('event-modal'); } });
</script>
@endpush