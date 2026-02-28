{{-- resources/views/amil/kunjungan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Kunjungan')

@push('styles')
<style>
    /* ============================================================
       VARIABLES & RESET
       ============================================================ */
    :root {
        --c-900: #0f2714;
        --c-800: #1a3d22;
        --c-700: #2d6936;
        --c-600: #3d8b40;
        --c-400: #7cb342;
        --c-100: #e6f4ea;
        --c-50:  #f3faf0;
        --gold:        #b8860b;
        --gold-bg:     #fdf6e3;
        --gold-border: #e8d5a0;
        --red:      #dc2626;
        --red-bg:   #fef2f2;
        --red-border:#fecaca;
        --orange:      #d97706;
        --orange-bg:   #fffbeb;
        --orange-border:#fde68a;
        --blue:        #2563eb;
        --blue-bg:     #eff6ff;
        --blue-border: #bfdbfe;
        --n-900: #111827;
        --n-700: #374151;
        --n-500: #6b7280;
        --n-400: #9ca3af;
        --n-200: #e5e7eb;
        --n-100: #f3f4f6;
        --n-50:  #f9fafb;
        --white: #ffffff;
        --radius:    16px;
        --radius-sm: 10px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md: 0 4px 16px -2px rgba(26,61,34,.10), 0 2px 6px -1px rgba(26,61,34,.06);
        --shadow-lg: 0 12px 32px -6px rgba(26,61,34,.18), 0 4px 12px -2px rgba(26,61,34,.10);
    }

    /* ============================================================
       STATUS PANELS (seperti di transaksi penyaluran)
       ============================================================ */
    .panel-dijadwalkan {
        background: var(--blue-bg);
        border: 1px solid var(--blue-border);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
    }
    .panel-dijadwalkan-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--blue);
    }
    .panel-dijadwalkan-sub {
        font-size: .8rem;
        color: #1e40af;
        margin-top: 4px;
    }

    .panel-selesai {
        background: var(--c-50);
        border: 1px solid var(--c-100);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
    }
    .panel-selesai-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--c-700);
    }
    .panel-selesai-sub {
        font-size: .8rem;
        color: var(--c-600);
        margin-top: 4px;
    }

    .panel-dibatalkan {
        background: var(--red-bg);
        border: 1px solid var(--red-border);
        border-radius: var(--radius-sm);
        padding: 1rem 1.25rem;
    }
    .panel-dibatalkan-title {
        font-size: .9rem;
        font-weight: 700;
        color: var(--red);
    }
    .panel-dibatalkan-sub {
        font-size: .8rem;
        color: #b91c1c;
        margin-top: 4px;
    }

    /* ============================================================
       STATUS BADGE
       ============================================================ */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid;
    }
    .status-badge-dijadwalkan {
        background: var(--blue-bg);
        color: var(--blue);
        border-color: var(--blue-border);
    }
    .status-badge-selesai {
        background: var(--c-50);
        color: var(--c-700);
        border-color: var(--c-100);
    }
    .status-badge-dibatalkan {
        background: var(--red-bg);
        color: var(--red);
        border-color: var(--red-border);
    }

    /* ============================================================
       CARD STYLES (sama dengan transaksi penyaluran)
       ============================================================ */
    .detail-card {
        background: var(--white);
        border-radius: var(--radius);
        border: 1px solid var(--n-200);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .detail-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--n-100);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--n-50);
    }

    .detail-card-title {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--n-400);
    }

    .detail-card-body {
        padding: 1.5rem;
    }

    /* ============================================================
       MUSTAHIK INFO CARD
       ============================================================ */
    .mustahik-profile {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        background: var(--c-50);
        border: 1px solid var(--c-100);
        border-radius: var(--radius-sm);
        padding: 1.25rem;
    }

    .mustahik-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--c-700), var(--c-400));
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px -2px rgba(45, 105, 54, 0.3);
    }

    .mustahik-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--c-800);
    }

    .mustahik-reg {
        font-size: 0.7rem;
        font-weight: 600;
        color: var(--c-600);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-top: 2px;
    }

    .mustahik-detail {
        font-size: 0.8rem;
        color: var(--n-600);
        margin-top: 8px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .mustahik-detail-row {
        display: flex;
        align-items: flex-start;
        gap: 6px;
    }

    .mustahik-link {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--c-700);
        text-decoration: none;
        margin-top: 8px;
    }
    .mustahik-link:hover {
        color: var(--c-600);
        text-decoration: underline;
    }

    /* ============================================================
       JADWAL ITEMS
       ============================================================ */
    .jadwal-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--n-100);
    }
    .jadwal-item:last-child {
        border-bottom: none;
    }

    .jadwal-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        background: var(--n-100);
        border: 1px solid var(--n-200);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .jadwal-label {
        font-size: 0.7rem;
        font-weight: 500;
        color: var(--n-400);
        margin-bottom: 2px;
    }

    .jadwal-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--n-900);
    }

    /* ============================================================
       HASIL KUNJUNGAN
       ============================================================ */
    .hasil-content {
        background: var(--c-50);
        border: 1px solid var(--c-100);
        border-radius: var(--radius-sm);
        padding: 1.25rem;
        font-size: 0.85rem;
        color: var(--n-700);
        line-height: 1.6;
        white-space: pre-line;
    }

    /* ============================================================
       FOTO GRID
       ============================================================ */
    .foto-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
    }

    .foto-item {
        position: relative;
        border-radius: var(--radius-sm);
        overflow: hidden;
        border: 1px solid var(--n-200);
        aspect-ratio: 1;
        background: var(--n-100);
    }

    .foto-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s;
    }
    .foto-item:hover img {
        transform: scale(1.05);
    }

    .foto-delete-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(220, 38, 38, 0.9);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .foto-item:hover .foto-delete-btn {
        opacity: 1;
    }

    /* ============================================================
       ACTION BUTTONS (sama dengan transaksi penyaluran)
       ============================================================ */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: var(--radius-sm);
        padding: 0.6rem 1.2rem;
        transition: all 0.15s;
        text-decoration: none;
        border: 1px solid;
        cursor: pointer;
    }

    .btn-sm {
        padding: 0.4rem 1rem;
        font-size: 0.7rem;
    }

    .btn-primary {
        background: var(--c-700);
        color: white;
        border-color: var(--c-700);
    }
    .btn-primary:hover {
        background: var(--c-600);
        border-color: var(--c-600);
    }

    .btn-outline {
        background: white;
        color: var(--n-700);
        border-color: var(--n-200);
    }
    .btn-outline:hover {
        background: var(--n-100);
    }

    .btn-success {
        background: var(--c-50);
        color: var(--c-700);
        border-color: var(--c-100);
    }
    .btn-success:hover {
        background: var(--c-100);
        border-color: var(--c-400);
    }

    .btn-warning {
        background: var(--orange-bg);
        color: var(--orange);
        border-color: var(--orange-border);
    }
    .btn-warning:hover {
        background: #fef3c7;
    }

    .btn-danger {
        background: var(--red-bg);
        color: var(--red);
        border-color: var(--red-border);
    }
    .btn-danger:hover {
        background: #fee2e2;
    }

    /* ============================================================
       FOOTER (sama dengan transaksi penyaluran)
       ============================================================ */
    .detail-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        padding: 1rem 1.5rem;
        background: var(--n-50);
        border-top: 1px solid var(--n-100);
    }

    .footer-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* ============================================================
       TIMESTAMPS (sama dengan transaksi penyaluran)
       ============================================================ */
    .timestamps {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.7rem;
        color: var(--n-400);
        padding: 0.75rem 1.5rem;
        border-top: 1px solid var(--n-100);
    }

    /* ============================================================
       MODAL (sama dengan transaksi penyaluran)
       ============================================================ */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 50;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(2px);
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.active {
        display: flex;
    }

    .modal-box {
        background: white;
        border-radius: var(--radius);
        padding: 1.75rem;
        width: 100%;
        max-width: 400px;
        box-shadow: var(--shadow-lg);
    }

    .modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--n-900);
        text-align: center;
        margin-bottom: 0.35rem;
    }

    .modal-text {
        font-size: 0.8rem;
        color: var(--n-500);
        text-align: center;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .modal-footer {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
    }

    .modal-btn-cancel {
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-sm);
        border: 1px solid var(--n-200);
        background: white;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--n-500);
        cursor: pointer;
    }
    .modal-btn-cancel:hover {
        background: var(--n-100);
    }

    .modal-btn-confirm {
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-sm);
        border: none;
        font-size: 0.8rem;
        font-weight: 700;
        color: white;
        cursor: pointer;
    }
    .modal-btn-confirm.warning {
        background: var(--orange);
    }
    .modal-btn-confirm.warning:hover {
        background: #b45309;
    }
    .modal-btn-confirm.danger {
        background: var(--red);
    }
    .modal-btn-confirm.danger:hover {
        background: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="space-y-5">

    {{-- ============================================================
         PANEL STATUS (seperti di transaksi penyaluran)
         ============================================================ --}}
    @if($kunjungan->status === 'direncanakan')
    <div class="panel-dijadwalkan">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="panel-dijadwalkan-title">Kunjungan Dijadwalkan</p>
                <p class="panel-dijadwalkan-sub">
                    Kunjungan ini masih berstatus direncanakan dan menunggu untuk dilaksanakan.
                </p>
            </div>
        </div>
    </div>
    @endif

    @if($kunjungan->status === 'selesai')
    <div class="panel-selesai">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="panel-selesai-title">Kunjungan Selesai</p>
                @if($kunjungan->diselesaikanOleh)
                <p class="panel-selesai-sub">
                    Diselesaikan oleh: <strong>{{ $kunjungan->diselesaikanOleh->nama ?? $kunjungan->diselesaikanOleh->name }}</strong>
                    @if($kunjungan->selesai_at)· {{ \Carbon\Carbon::parse($kunjungan->selesai_at)->translatedFormat('d F Y H:i') }}@endif
                </p>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($kunjungan->status === 'dibatalkan')
    <div class="panel-dibatalkan">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="panel-dibatalkan-title">Kunjungan Dibatalkan</p>
                @if($kunjungan->alasan_pembatalan)
                <p class="panel-dibatalkan-sub">Alasan: {{ $kunjungan->alasan_pembatalan }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================
         MAIN CARD
         ============================================================ --}}
    <div class="detail-card">

        {{-- Header --}}
        <div class="detail-card-header">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Detail Kunjungan Mustahik</h2>
                <p class="text-xs text-gray-500 mt-0.5">Informasi lengkap rencana dan hasil kunjungan</p>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $badgeClass = match($kunjungan->status) {
                        'direncanakan' => 'status-badge-dijadwalkan',
                        'selesai' => 'status-badge-selesai',
                        'dibatalkan' => 'status-badge-dibatalkan',
                        default => ''
                    };
                    $badgeLabel = match($kunjungan->status) {
                        'direncanakan' => 'Direncanakan',
                        'selesai' => '✓ Selesai',
                        'dibatalkan' => '✕ Dibatalkan',
                        default => ucfirst($kunjungan->status)
                    };
                @endphp
                <span class="status-badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
        </div>

        {{-- Content Body --}}
        <div class="detail-card-body space-y-6">

            {{-- Info Cards (3 kolom) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tanggal Kunjungan</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium">{{ $kunjungan->tanggal_kunjungan->format('d F Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $kunjungan->waktu_format }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Tujuan Kunjungan</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <div>
                            <p class="font-medium">{{ $kunjungan->tujuan_label }}</p>
                            @if($kunjungan->status === 'selesai')
                            <p class="text-xs text-gray-500">Selesai</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Amil</label>
                    <div class="flex items-start text-sm text-gray-900 gap-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <p class="font-medium">{{ $kunjungan->amil->nama_lengkap ?? auth()->user()->name }}</p>
                            @if($kunjungan->amil?->kode_amil)
                            <p class="text-xs text-gray-500">Kode: {{ $kunjungan->amil->kode_amil }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Informasi Mustahik --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Informasi Mustahik</h4>
                <div class="mustahik-profile">
                    <div class="mustahik-avatar">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="mustahik-name">{{ $kunjungan->mustahik->nama_lengkap }}</p>
                        <p class="mustahik-reg">{{ $kunjungan->mustahik->no_registrasi ?? '-' }}</p>
                        <div class="mustahik-detail">
                            @if($kunjungan->mustahik->nik)
                            <div class="mustahik-detail-row">
                                <svg class="w-3 h-3 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                                <span>NIK: {{ $kunjungan->mustahik->nik }}</span>
                            </div>
                            @endif
                            @if($kunjungan->mustahik->telepon)
                            <div class="mustahik-detail-row">
                                <svg class="w-3 h-3 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>{{ $kunjungan->mustahik->telepon }}</span>
                            </div>
                            @endif
                            @if($kunjungan->mustahik->alamat)
                            <div class="mustahik-detail-row">
                                <svg class="w-3 h-3 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>{{ $kunjungan->mustahik->alamat }}</span>
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('mustahik.show', $kunjungan->mustahik->uuid) }}" class="mustahik-link">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Lihat Profil Lengkap
                        </a>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- Jadwal Detail --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Detail Jadwal</h4>
                <div class="space-y-1">
                    <div class="jadwal-item">
                        <div class="jadwal-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-label">Tanggal</p>
                            <p class="jadwal-value">{{ $kunjungan->tanggal_kunjungan->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    <div class="jadwal-item">
                        <div class="jadwal-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-label">Waktu</p>
                            <p class="jadwal-value">{{ $kunjungan->waktu_format }}</p>
                        </div>
                    </div>
                    <div class="jadwal-item">
                        <div class="jadwal-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-label">Tujuan</p>
                            <p class="jadwal-value">{{ $kunjungan->tujuan_label }}</p>
                        </div>
                    </div>
                    @if($kunjungan->catatan)
                    <div class="jadwal-item">
                        <div class="jadwal-icon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-label">Catatan Rencana</p>
                            <p class="jadwal-value" style="font-weight:400;">{{ $kunjungan->catatan }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($kunjungan->status === 'selesai')
            <hr class="border-gray-200">

            {{-- Hasil Kunjungan --}}
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-900">Hasil Kunjungan</h4>
                    <a href="{{ route('amil.kunjungan.finish', $kunjungan->uuid) }}" class="btn btn-sm btn-success">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Hasil
                    </a>
                </div>
                <div class="hasil-content">
                    {{ $kunjungan->hasil_kunjungan ?? '-' }}
                </div>
            </div>

            {{-- Foto Dokumentasi --}}
            @if($kunjungan->foto_dokumentasi && count($kunjungan->foto_dokumentasi) > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-semibold text-gray-900">Foto Dokumentasi</h4>
                    <span class="text-xs text-gray-500">{{ count($kunjungan->foto_dokumentasi) }} foto</span>
                </div>
                <div class="foto-grid">
                    @foreach($kunjungan->foto_dokumentasi as $index => $foto)
                    <div class="foto-item">
                        <a href="{{ Storage::url($foto) }}" target="_blank">
                            <img src="{{ Storage::url($foto) }}" alt="Dokumentasi {{ $index+1 }}">
                        </a>
                        <form action="{{ route('amil.kunjungan.hapus-foto', $kunjungan->uuid) }}" method="POST" class="foto-delete-btn">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="index" value="{{ $index }}">
                            <button type="submit" onclick="return confirm('Hapus foto ini?')" class="w-full h-full flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            {{-- Riwayat Status (seperti di transaksi penyaluran) --}}
            @if($kunjungan->status !== 'direncanakan')
            <hr class="border-gray-200">
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-4">Riwayat Status</h4>
                <ol class="relative border-l border-gray-200 space-y-3 ml-3">
                    <li class="pl-6">
                        <div class="absolute -left-1.5 w-3 h-3 bg-gray-400 rounded-full border-2 border-white"></div>
                        <p class="text-xs font-semibold text-gray-900">Direncanakan</p>
                        <p class="text-xs text-gray-500">{{ $kunjungan->created_at->translatedFormat('d F Y H:i') }}</p>
                    </li>
                    @if($kunjungan->selesai_at)
                    <li class="pl-6">
                        <div class="absolute -left-1.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                        <p class="text-xs font-semibold text-gray-900">Selesai</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kunjungan->selesai_at)->translatedFormat('d F Y H:i') }}</p>
                    </li>
                    @endif
                    @if($kunjungan->dibatalkan_at)
                    <li class="pl-6">
                        <div class="absolute -left-1.5 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
                        <p class="text-xs font-semibold text-gray-900">Dibatalkan</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($kunjungan->dibatalkan_at)->translatedFormat('d F Y H:i') }}</p>
                    </li>
                    @endif
                </ol>
            </div>
            @endif

        </div>{{-- end detail-card-body --}}

        {{-- Timestamps (seperti di transaksi penyaluran) --}}
        <div class="timestamps">
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Dibuat: {{ $kunjungan->created_at->translatedFormat('d F Y H:i') }}
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Diperbarui: {{ $kunjungan->updated_at->translatedFormat('d F Y H:i') }}
            </div>
        </div>

        {{-- Footer Actions (seperti di transaksi penyaluran) --}}
        <div class="detail-footer">
            <a href="{{ route('amil.kunjungan.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar
            </a>
            <div class="footer-actions">
                @if($kunjungan->status === 'direncanakan')
                <a href="{{ route('amil.kunjungan.finish', $kunjungan->uuid) }}" class="btn btn-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai Selesai
                </a>
                <a href="{{ route('amil.kunjungan.edit', $kunjungan->uuid) }}" class="btn btn-outline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Jadwal
                </a>
                <button type="button" class="btn btn-warning" onclick="openModal('cancel-modal')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Batalkan
                </button>
                @endif

                @if($kunjungan->status === 'selesai')
                <a href="{{ route('amil.kunjungan.finish', $kunjungan->uuid) }}" class="btn btn-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Hasil
                </a>
                @endif

                <button type="button" class="btn btn-danger" onclick="openModal('delete-modal')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>

    </div>{{-- end detail-card --}}
</div>

{{-- Modal Batalkan --}}
<div class="modal-overlay" id="cancel-modal">
    <div class="modal-box">
        <div class="modal-icon" style="background:var(--orange-bg); border:1px solid var(--orange-border);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--orange)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="modal-title">Batalkan Kunjungan?</h3>
        <p class="modal-text">
            Kunjungan dengan <strong>{{ $kunjungan->mustahik->nama_lengkap }}</strong><br>
            pada {{ $kunjungan->tanggal_kunjungan->format('d M Y') }} akan dibatalkan.
            Tindakan ini tidak dapat diurungkan.
        </p>
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" onclick="closeModal('cancel-modal')">Tidak</button>
            <form action="{{ route('amil.kunjungan.cancel', $kunjungan->uuid) }}" method="POST">
                @csrf
                <button type="submit" class="modal-btn-confirm warning">Ya, Batalkan</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-box">
        <div class="modal-icon" style="background:var(--red-bg); border:1px solid var(--red-border);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="modal-title">Hapus Kunjungan?</h3>
        <p class="modal-text">
            Data kunjungan dan semua foto dokumentasi akan dihapus permanen dan tidak bisa dipulihkan.
        </p>
        <div class="modal-footer">
            <button type="button" class="modal-btn-cancel" onclick="closeModal('delete-modal')">Batal</button>
            <form action="{{ route('amil.kunjungan.destroy', $kunjungan->uuid) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm danger">Hapus Permanen</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(el => {
                el.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
        }
    });
</script>
@endpush