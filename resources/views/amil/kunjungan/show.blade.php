{{-- resources/views/amil/kunjungan/show.blade.php --}}
@extends('layouts.app')
@section('title', 'Detail Kunjungan')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Poppins', sans-serif !important; }

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

        /* ── PAGE HEADER ── */
        .page-header {
            background: #2d6a2d;
            border-radius: var(--radius);
            padding: 1.75rem 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            margin-bottom: 1.5rem;
        }

        .page-header-decor {
            position: absolute;
            right: -48px; top: -64px;
            width: 220px; height: 220px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.06);
            pointer-events: none;
        }

        .page-header-decor::after {
            content: '';
            position: absolute;
            inset: 28px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.04);
        }

        .page-header-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .page-header-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .72rem;
            font-weight: 600;
            color: rgba(255,255,255,.65);
            letter-spacing: .04em;
            text-decoration: none;
            margin-bottom: .6rem;
            transition: color .15s;
        }

        .page-header-back:hover { color: #fff; }

        .page-header-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.03em;
            line-height: 1.2;
        }

        .page-header-sub {
            font-size: .8rem;
            font-weight: 400;
            color: rgba(255,255,255,.55);
            margin-top: .2rem;
        }

        /* ── STATUS BADGE ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            border-radius: 999px;
            padding: 5px 14px;
            border: 1px solid;
        }

        .status-pill-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-pill.dijadwalkan   { color:#1d4ed8; background:#eff6ff; border-color:#bfdbfe; }
        .status-pill.dijadwalkan .status-pill-dot { background:#3b82f6; }
        .status-pill.selesai       { color:var(--c-700); background:var(--c-50); border-color:var(--c-100); }
        .status-pill.selesai .status-pill-dot { background:var(--c-400); }
        .status-pill.dibatalkan    { color:var(--red); background:var(--red-bg); border-color:var(--red-border); }
        .status-pill.dibatalkan .status-pill-dot { background:var(--red); }

        /* ── PANEL ── */
        .panel {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--n-200);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .panel-head {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--n-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--n-50);
        }

        .panel-title {
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: var(--n-400);
        }

        .panel-body {
            padding: 1.5rem;
        }

        /* ── MUSTAHIK CARD ── */
        .mustahik-card {
            background: var(--c-50);
            border: 1px solid var(--c-100);
            border-radius: var(--radius-sm);
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .mustahik-avatar {
            width: 48px; height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--c-700), var(--c-400));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 10px -2px rgba(45,105,54,.30);
        }

        .mustahik-name {
            font-size: .95rem;
            font-weight: 700;
            color: var(--c-800);
            letter-spacing: -.01em;
        }

        .mustahik-reg {
            font-size: .7rem;
            font-weight: 600;
            color: var(--c-600);
            margin-top: 2px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .mustahik-meta {
            font-size: .73rem;
            font-weight: 500;
            color: var(--n-500);
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .mustahik-meta-row {
            display: flex;
            align-items: flex-start;
            gap: 5px;
        }

        .mustahik-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .72rem;
            font-weight: 700;
            color: var(--c-700);
            text-decoration: none;
            margin-top: .6rem;
            transition: color .15s;
        }

        .mustahik-link:hover { color: var(--c-600); text-decoration: underline; }

        /* ── JADWAL ITEMS ── */
        .jadwal-item {
            display: flex;
            align-items: flex-start;
            gap: .85rem;
            padding: .75rem 0;
            border-bottom: 1px solid var(--n-100);
        }

        .jadwal-item:last-child { border-bottom: none; }

        .jadwal-icon-wrap {
            width: 38px; height: 38px;
            border-radius: var(--radius-sm);
            background: var(--n-100);
            border: 1px solid var(--n-200);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .jadwal-lbl {
            font-size: .68rem;
            font-weight: 500;
            color: var(--n-400);
            margin-bottom: 2px;
        }

        .jadwal-val {
            font-size: .85rem;
            font-weight: 700;
            color: var(--n-900);
            letter-spacing: -.01em;
        }

        /* ── HASIL KUNJUNGAN ── */
        .hasil-block {
            background: var(--c-50);
            border: 1px solid var(--c-100);
            border-radius: var(--radius-sm);
            padding: 1.25rem;
            font-size: .85rem;
            font-weight: 500;
            color: var(--n-700);
            line-height: 1.65;
            white-space: pre-line;
        }

        /* ── FOTO GRID ── */
        .foto-item {
            position: relative;
            border-radius: var(--radius-sm);
            overflow: hidden;
            border: 1px solid var(--n-200);
            aspect-ratio: 1;
            background: var(--n-100);
            transition: box-shadow .2s;
        }

        .foto-item:hover { box-shadow: var(--shadow-md); }

        .foto-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .25s;
        }

        .foto-item:hover img { transform: scale(1.05); }

        .foto-del-btn {
            position: absolute;
            top: 6px; right: 6px;
            width: 26px; height: 26px;
            border-radius: 50%;
            background: rgba(220,38,38,.9);
            border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            opacity: 0;
            transform: scale(.8);
            transition: opacity .2s, transform .2s;
        }

        .foto-item:hover .foto-del-btn {
            opacity: 1;
            transform: scale(1);
        }

        /* ── DIBATALKAN BLOCK ── */
        .cancel-block {
            background: var(--red-bg);
            border: 1px solid var(--red-border);
            border-radius: var(--radius-sm);
            padding: 1rem 1.25rem;
        }

        .cancel-block-title {
            font-size: .83rem;
            font-weight: 700;
            color: var(--red);
        }

        .cancel-block-sub {
            font-size: .78rem;
            color: #b91c1c;
            margin-top: 4px;
        }

        /* ── ACTION BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .78rem;
            font-weight: 700;
            border-radius: var(--radius-sm);
            padding: .55rem 1.1rem;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            border: 1px solid;
            letter-spacing: .01em;
        }

        .btn-ghost {
            color: var(--n-700);
            background: var(--white);
            border-color: var(--n-200);
        }

        .btn-ghost:hover {
            background: var(--n-100);
        }

        .btn-green {
            color: var(--c-700);
            background: var(--c-50);
            border-color: var(--c-100);
        }

        .btn-green:hover {
            background: var(--c-100);
            border-color: var(--c-400);
        }

        .btn-orange {
            color: var(--orange);
            background: var(--orange-bg);
            border-color: var(--orange-border);
        }

        .btn-orange:hover {
            background: #fef3c7;
        }

        .btn-red {
            color: var(--red);
            background: var(--red-bg);
            border-color: var(--red-border);
        }

        .btn-red:hover {
            background: #fee2e2;
        }

        /* ── FOOTER ── */
        .page-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            padding: 1rem 1.5rem;
            background: var(--n-50);
            border-top: 1px solid var(--n-100);
            border-radius: 0 0 var(--radius) var(--radius);
        }

        .footer-actions {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            background: rgba(0,0,0,.45);
            backdrop-filter: blur(2px);
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-overlay.active { display: flex; }

        .modal-box {
            background: #fff;
            border-radius: var(--radius);
            padding: 1.75rem;
            width: 100%;
            max-width: 400px;
            box-shadow: var(--shadow-lg);
        }

        .modal-icon {
            width: 52px; height: 52px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
        }

        .modal-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--n-900);
            text-align: center;
            margin-bottom: .35rem;
        }

        .modal-sub {
            font-size: .78rem;
            color: var(--n-500);
            text-align: center;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: .5rem;
        }

        .modal-cancel-btn {
            padding: .5rem 1.25rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--n-200);
            background: #fff;
            font-size: .78rem;
            font-weight: 600;
            color: var(--n-500);
            cursor: pointer;
            font-family: inherit;
            transition: all .15s;
        }

        .modal-cancel-btn:hover { background: var(--n-100); }

        .modal-confirm-orange {
            padding: .5rem 1.25rem;
            border-radius: var(--radius-sm);
            border: none;
            background: var(--orange);
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .modal-confirm-orange:hover { background: #b45309; }

        .modal-confirm-red {
            padding: .5rem 1.25rem;
            border-radius: var(--radius-sm);
            border: none;
            background: var(--red);
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .modal-confirm-red:hover { background: #b91c1c; }
    </style>
@endpush

@section('content')
<div class="space-y-5">

    {{-- Page Header (Hero) --}}
    <div class="page-header">
        <div class="page-header-decor"></div>
        <div class="page-header-inner">
            <div>
                <a href="{{ route('amil.kunjungan.index') }}" class="page-header-back">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Kunjungan
                </a>
                <h1 class="page-header-title">Detail Kunjungan</h1>
                <p class="page-header-sub">
                    {{ $kunjungan->mustahik->nama_lengkap }}
                    &mdash;
                    {{ $kunjungan->tanggal_kunjungan->translatedFormat('d F Y') }}
                </p>
            </div>
            <div>
                {{-- Status Badge --}}
                @php
                    $statusClass = match($kunjungan->status ?? '') {
                        'selesai'    => 'selesai',
                        'dibatalkan' => 'dibatalkan',
                        default      => 'dijadwalkan',
                    };
                    $statusLabel = match($kunjungan->status ?? '') {
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default      => 'Dijadwalkan',
                    };
                @endphp
                <span class="status-pill {{ $statusClass }}">
                    <span class="status-pill-dot"></span>
                    {{ $statusLabel }}
                </span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

        {{-- LEFT: Mustahik + Jadwal --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Mustahik --}}
            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Mustahik</p>
                </div>
                <div class="panel-body">
                    <div class="mustahik-card">
                        <div class="mustahik-avatar">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="mustahik-name">{{ $kunjungan->mustahik->nama_lengkap }}</p>
                            <p class="mustahik-reg">{{ $kunjungan->mustahik->no_registrasi }}</p>
                            <div class="mustahik-meta">
                                @if($kunjungan->mustahik->alamat)
                                <span class="mustahik-meta-row">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--n-400)">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>{{ $kunjungan->mustahik->alamat }}</span>
                                </span>
                                @endif
                                @if($kunjungan->mustahik->telepon)
                                <span class="mustahik-meta-row">
                                    <svg class="w-3 h-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--n-400)">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span>{{ $kunjungan->mustahik->telepon }}</span>
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('mustahik.show', $kunjungan->mustahik->uuid) }}" class="mustahik-link">
                                Lihat profil lengkap
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jadwal --}}
            <div class="panel">
                <div class="panel-head">
                    <p class="panel-title">Jadwal Kunjungan</p>
                </div>
                <div class="panel-body" style="padding-top:1rem; padding-bottom:1rem;">

                    <div class="jadwal-item">
                        <div class="jadwal-icon-wrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-lbl">Tanggal</p>
                            <p class="jadwal-val">{{ $kunjungan->tanggal_kunjungan->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>

                    <div class="jadwal-item">
                        <div class="jadwal-icon-wrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-lbl">Waktu</p>
                            <p class="jadwal-val">{{ $kunjungan->waktu_format }}</p>
                        </div>
                    </div>

                    <div class="jadwal-item">
                        <div class="jadwal-icon-wrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-lbl">Tujuan</p>
                            <p class="jadwal-val">{{ $kunjungan->tujuan_label }}</p>
                        </div>
                    </div>

                    @if($kunjungan->catatan)
                    <div class="jadwal-item">
                        <div class="jadwal-icon-wrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--c-700)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="jadwal-lbl">Catatan Rencana</p>
                            <p class="jadwal-val" style="font-weight:500; color:var(--n-700);">{{ $kunjungan->catatan }}</p>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>

        {{-- RIGHT: Hasil + Foto / Dibatalkan --}}
        <div class="lg:col-span-3 space-y-5">

            @if($kunjungan->isSelesai())

                {{-- Hasil Kunjungan --}}
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Hasil Kunjungan</p>
                        @if($kunjungan->isEditable() || $kunjungan->isSelesai())
                        <a href="{{ route('amil.kunjungan.finish', $kunjungan->uuid) }}" class="btn btn-green" style="font-size:.68rem; padding:.35rem .85rem;">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Hasil
                        </a>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div class="hasil-block">{{ $kunjungan->hasil_kunjungan ?? '-' }}</div>
                    </div>
                </div>

                {{-- Foto Dokumentasi --}}
                @if($kunjungan->foto_dokumentasi_urls)
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Foto Dokumentasi</p>
                        <span style="font-size:.7rem; font-weight:600; color:var(--n-400);">
                            {{ count($kunjungan->foto_dokumentasi_urls) }} foto
                        </span>
                    </div>
                    <div class="panel-body">
                        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap:.75rem;">
                            @foreach($kunjungan->foto_dokumentasi_urls as $i => $url)
                            <div class="foto-item">
                                <a href="{{ $url }}" target="_blank">
                                    <img src="{{ $url }}" alt="Foto {{ $i+1 }}">
                                </a>
                                <form method="POST" action="{{ route('amil.kunjungan.hapus-foto', $kunjungan->uuid) }}"
                                    onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="index" value="{{ $i }}">
                                    <button type="submit" class="foto-del-btn" title="Hapus foto">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            @elseif($kunjungan->isDibatalkan())

                {{-- Dibatalkan --}}
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Status Kunjungan</p>
                    </div>
                    <div class="panel-body">
                        <div class="cancel-block">
                            <p class="cancel-block-title">Kunjungan ini telah dibatalkan.</p>
                            @if($kunjungan->catatan)
                            <p class="cancel-block-sub">{{ $kunjungan->catatan }}</p>
                            @endif
                        </div>
                    </div>
                </div>

            @else

                {{-- Belum selesai — tampilkan tombol aksi utama --}}
                <div class="panel">
                    <div class="panel-head">
                        <p class="panel-title">Tindakan</p>
                    </div>
                    <div class="panel-body">
                        <p style="font-size:.8rem; color:var(--n-500); margin-bottom:1rem;">
                            Kunjungan ini belum ditandai selesai. Tandai selesai untuk mengisi hasil kunjungan dan dokumentasi.
                        </p>
                        <div style="display:flex; gap:.5rem; flex-wrap:wrap;">
                            <a href="{{ route('amil.kunjungan.finish', $kunjungan->uuid) }}" class="btn btn-green">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                                Tandai Selesai
                            </a>
                            @if($kunjungan->isEditable())
                            <a href="{{ route('amil.kunjungan.edit', $kunjungan->uuid) }}" class="btn btn-ghost">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit Jadwal
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="panel">
        <div class="page-footer">
            <a href="{{ route('amil.kunjungan.index') }}" class="btn btn-ghost">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <div class="footer-actions">
                @if($kunjungan->isEditable())
                <button type="button" class="btn btn-orange" onclick="openModal('cancel-modal')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Batalkan Kunjungan
                </button>
                @endif
                <button type="button" class="btn btn-red" onclick="openModal('delete-modal')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>

</div>

{{-- Modal Batalkan --}}
<div class="modal-overlay" id="cancel-modal">
    <div class="modal-box">
        <div class="modal-icon" style="background:var(--orange-bg); border: 1px solid var(--orange-border);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--orange)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h3 class="modal-title">Batalkan Kunjungan?</h3>
        <p class="modal-sub">
            Kunjungan dengan <strong>{{ $kunjungan->mustahik->nama_lengkap }}</strong>
            pada {{ $kunjungan->tanggal_kunjungan->format('d M Y') }} akan dibatalkan.
            Tindakan ini tidak dapat diurungkan.
        </p>
        <div class="modal-footer">
            <button type="button" class="modal-cancel-btn" onclick="closeModal('cancel-modal')">Tidak</button>
            <form action="{{ route('amil.kunjungan.cancel', $kunjungan->uuid) }}" method="POST">
                @csrf
                <button type="submit" class="modal-confirm-orange">Ya, Batalkan</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal-box">
        <div class="modal-icon" style="background:var(--red-bg); border: 1px solid var(--red-border);">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red)">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </div>
        <h3 class="modal-title">Hapus Kunjungan?</h3>
        <p class="modal-sub">Data kunjungan dan semua foto dokumentasi akan dihapus permanen dan tidak bisa dipulihkan.</p>
        <div class="modal-footer">
            <button type="button" class="modal-cancel-btn" onclick="closeModal('delete-modal')">Batal</button>
            <form action="{{ route('amil.kunjungan.destroy', $kunjungan->uuid) }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="modal-confirm-red">Hapus Permanen</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }
    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });
    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(el => {
                el.classList.remove('active');
            });
        }
    });
</script>
@endpush

@endsection