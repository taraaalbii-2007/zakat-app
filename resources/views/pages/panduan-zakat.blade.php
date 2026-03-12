@extends('layouts.guest')

@section('title', 'Panduan Zakat')

@section('content')

    @include('partials.landing.page-hero', [
        'breadcrumb'   => 'Panduan Zakat',
        'badge'        => 'Panduan Zakat',
        'heroTitle'    => 'Panduan Zakat',
        'heroSubtitle' => 'Informasi lengkap jenis zakat, metode penerimaan, dan cara pembayaran yang tersedia di sistem kami.'
    ])

    {{-- ══ HERO TITLE UNDERLINE (seperti di gambar) ══ --}}
    <style>
    /* ══════════════════════════════════════════════════════════
       HERO UNDERLINE — animated grow like the reference image
    ══════════════════════════════════════════════════════════ */
    .hero-underline-wrapper {
        display: flex;
        justify-content: center;
        margin-top: -8px;
        margin-bottom: 0;
    }
    .hero-underline {
        height: 4px;
        width: 0;
        background: linear-gradient(to right, #16a34a, #4ade80);
        border-radius: 99px;
        animation: heroLineGrow 0.9s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
    }
    @keyframes heroLineGrow {
        from { width: 0; opacity: 0; }
        to   { width: 56px; opacity: 1; }
    }

    /* ══════════════════════════════════════════════════════════
       KEYFRAMES — extended set
    ══════════════════════════════════════════════════════════ */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeDown {
        from { opacity: 0; transform: translateY(-20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeLeft {
        from { opacity: 0; transform: translateX(24px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeRight {
        from { opacity: 0; transform: translateX(-24px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes scalePop {
        from { opacity: 0; transform: scale(0.86); }
        to   { opacity: 1; transform: scale(1); }
    }
    @keyframes badgePop {
        0%   { opacity: 0; transform: scale(0.65) translateY(10px); }
        65%  { transform: scale(1.08) translateY(-2px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes floatIn {
        from { opacity: 0; transform: translateY(36px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }
    @keyframes rowSlide {
        from { opacity: 0; transform: translateX(-14px); }
        to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position: 200% center; }
    }
    @keyframes dotPulse {
        0%, 100% { box-shadow: 0 0 0 3px rgba(22,163,74,0.12); }
        50%       { box-shadow: 0 0 0 6px rgba(22,163,74,0.06); }
    }
    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
        50%       { box-shadow: 0 0 16px 2px rgba(22,163,74,0.12); }
    }

    /* ── NEW per-section keyframes ────────────────────────── */

    /* Section 1 — Jenis Zakat: cards flip in from bottom-left */
    @keyframes flipInBL {
        from { opacity: 0; transform: perspective(600px) rotateX(12deg) translateY(20px); }
        to   { opacity: 1; transform: perspective(600px) rotateX(0deg) translateY(0); }
    }

    /* Section 2 — Zakat Fitrah: gentle zoom-fade */
    @keyframes zoomFade {
        from { opacity: 0; transform: scale(0.92) translateY(12px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Section 3 — Zakat Mal: slide from right with blur */
    @keyframes slideBlurRight {
        from { opacity: 0; transform: translateX(32px); filter: blur(4px); }
        to   { opacity: 1; transform: translateX(0); filter: blur(0); }
    }

    /* Section 4 — Zakat Profesi: cascade drop */
    @keyframes cascadeDrop {
        from { opacity: 0; transform: translateY(-18px) scaleY(0.9); }
        to   { opacity: 1; transform: translateY(0) scaleY(1); }
    }

    /* Section 5 — Zakat Pertanian: earth rise */
    @keyframes earthRise {
        from { opacity: 0; transform: translateY(22px) rotate(-1.5deg); }
        to   { opacity: 1; transform: translateY(0) rotate(0deg); }
    }

    /* Section 6 — Zakat Ternak: horizontal sweep */
    @keyframes sweepLeft {
        from { opacity: 0; transform: translateX(-28px) skewX(3deg); }
        to   { opacity: 1; transform: translateX(0) skewX(0deg); }
    }

    /* Section 7 — Zakat Perniagaan: spring pop */
    @keyframes springPop {
        0%   { opacity: 0; transform: scale(0.80); }
        60%  { transform: scale(1.04); }
        80%  { transform: scale(0.98); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Section 8 — Zakat Rikaz: diagonal reveal */
    @keyframes diagonalReveal {
        from { opacity: 0; transform: translate(14px, -14px); }
        to   { opacity: 1; transform: translate(0, 0); }
    }

    /* Section 9 — Fidyah: soft upward drift */
    @keyframes softDrift {
        from { opacity: 0; transform: translateY(16px); filter: blur(2px); }
        to   { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    /* Section 10 — Metode Penerimaan: cards spread from center */
    @keyframes spreadIn {
        from { opacity: 0; transform: scale(0.88) translateY(10px); }
        to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* Section 11 — Metode Pembayaran: wave entrance */
    @keyframes waveIn {
        from { opacity: 0; transform: translateX(-20px) translateY(8px); }
        to   { opacity: 1; transform: translateX(0) translateY(0); }
    }

    /* Section 12 — Mustahik: numbered badge burst */
    @keyframes badgeBurst {
        0%   { opacity: 0; transform: scale(0.6) rotate(-8deg); }
        70%  { transform: scale(1.05) rotate(1deg); }
        100% { opacity: 1; transform: scale(1) rotate(0deg); }
    }

    /* ══════════════════════════════════════════════════════════
       ANIMATION UTILITY CLASSES
    ══════════════════════════════════════════════════════════ */
    .anim-ready { opacity: 0; }

    /* Generic */
    .anim-fadeup       { animation: fadeUp       0.65s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-fadedown     { animation: fadeDown     0.55s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-fadeleft     { animation: fadeLeft     0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-faderight    { animation: fadeRight    0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-scalepop     { animation: scalePop     0.55s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .anim-badgepop     { animation: badgePop     0.55s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .anim-floatin      { animation: floatIn      0.75s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-rowslide     { animation: rowSlide     0.45s cubic-bezier(0.16,1,0.3,1) forwards; }

    /* Per-section specifics */
    .anim-flipinbl     { animation: flipInBL     0.7s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-zoomfade     { animation: zoomFade     0.65s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-slideblur    { animation: slideBlurRight 0.65s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-cascade      { animation: cascadeDrop  0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-earthrise    { animation: earthRise    0.7s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-sweepleft    { animation: sweepLeft    0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-springpop    { animation: springPop    0.65s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .anim-diagonal     { animation: diagonalReveal 0.55s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-softdrift    { animation: softDrift    0.7s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-spreadin     { animation: spreadIn     0.65s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .anim-wavein       { animation: waveIn       0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
    .anim-badgeburst   { animation: badgeBurst   0.6s cubic-bezier(0.34,1.56,0.64,1) forwards; }

    /* Stagger delays */
    .d-0  { animation-delay: 0ms; }
    .d-1  { animation-delay: 80ms; }
    .d-2  { animation-delay: 160ms; }
    .d-3  { animation-delay: 240ms; }
    .d-4  { animation-delay: 320ms; }
    .d-5  { animation-delay: 400ms; }
    .d-6  { animation-delay: 480ms; }
    .d-7  { animation-delay: 560ms; }
    .d-8  { animation-delay: 640ms; }
    .d-9  { animation-delay: 720ms; }
    .d-10 { animation-delay: 800ms; }
    .d-11 { animation-delay: 880ms; }

    /* ══════════════════════════════════════════════════════════
       HEADING BAR — animated grow
    ══════════════════════════════════════════════════════════ */
    .section-heading-bar {
        width: 4px; height: 0;
        background: linear-gradient(to bottom, #16a34a, #4ade80);
        border-radius: 99px;
        flex-shrink: 0;
        transition: height 0.6s cubic-bezier(0.16,1,0.3,1);
    }
    .section-heading-bar.bar-grown { height: 28px; }

    /* ══════════════════════════════════════════════════════════
       BASE LAYOUT
    ══════════════════════════════════════════════════════════ */
    .panduan-nav a {
        position: relative;
        transition: color 0.22s, background 0.22s, padding-left 0.22s;
    }
    .panduan-nav a::before {
        content: '';
        position: absolute;
        left: 0; top: 50%;
        transform: translateY(-50%);
        width: 0; height: 14px;
        background: #16a34a;
        border-radius: 0 3px 3px 0;
        transition: width 0.22s ease;
    }
    .panduan-nav a:hover::before,
    .panduan-nav a.active::before { width: 3px; }
    .panduan-nav a:hover,
    .panduan-nav a.active {
        color: #15803d;
        background: #f0fdf4;
        padding-left: 1.1rem;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    /* ══════════════════════════════════════════════════════════
       CARDS & TILES
    ══════════════════════════════════════════════════════════ */
    .info-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        transition: border-color 0.28s, box-shadow 0.28s, transform 0.28s;
    }
    .info-card:hover {
        border-color: rgba(22,163,74,0.30);
        box-shadow: 0 6px 24px rgba(22,163,74,0.08);
        transform: translateY(-3px);
    }

    .stat-tile {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid rgba(22,163,74,0.14);
        border-radius: 14px;
        padding: 1.4rem 1rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        transition: transform 0.28s, box-shadow 0.28s;
    }
    .stat-tile:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 8px 28px rgba(22,163,74,0.12);
    }
    .stat-tile::after {
        content: '';
        position: absolute;
        top: -16px; right: -16px;
        width: 60px; height: 60px;
        background: rgba(22,163,74,0.07);
        border-radius: 50%;
        pointer-events: none;
    }
    .stat-tile::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(105deg,
            transparent 40%,
            rgba(255,255,255,0.45) 50%,
            transparent 60%);
        background-size: 200% 100%;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .stat-tile:hover::before {
        opacity: 1;
        animation: shimmer 0.7s ease forwards;
    }

    .step-badge {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #4ade80);
        color: white;
        font-size: 11px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(22,163,74,0.28);
        transition: transform 0.22s, box-shadow 0.22s;
    }
    .info-card:hover .step-badge {
        transform: scale(1.15);
        box-shadow: 0 4px 14px rgba(22,163,74,0.38);
    }

    .status-pill {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 99px;
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid rgba(22,163,74,0.18);
        white-space: nowrap;
    }

    .method-header {
        background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        padding: 0.9rem 1.1rem;
        border-radius: 15px 15px 0 0;
    }

    .cta-block {
        background: linear-gradient(135deg, #15803d 0%, #16a34a 50%, #22c55e 100%);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .cta-block::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
        pointer-events: none;
    }
    .cta-block::after {
        content: '';
        position: absolute;
        bottom: -50px; left: -30px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
        pointer-events: none;
    }

    .section-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e5e7eb 20%, #e5e7eb 80%, transparent);
        border: none;
        margin: 0;
    }

    .sidebar-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        box-shadow: 0 1px 12px rgba(0,0,0,0.05);
    }
    .sidebar-header {
        background: linear-gradient(135deg, #16a34a, #15803d);
        padding: 1.1rem 1.25rem;
    }

    .note-box {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid rgba(22,163,74,0.18);
        border-left: 3px solid #16a34a;
        border-radius: 10px;
        padding: 0.9rem 1.1rem;
        font-size: 0.8125rem;
        color: #166534;
        line-height: 1.65;
    }

    .note-box-amber {
        background: #fffbeb;
        border: 1px solid rgba(245,158,11,0.20);
        border-left: 3px solid #f59e0b;
        border-radius: 10px;
        padding: 0.9rem 1.1rem;
        font-size: 0.8125rem;
        color: #78350f;
        line-height: 1.65;
    }

    .formula-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.9rem 1.25rem;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        color: #374151;
        position: relative;
        overflow: hidden;
    }
    .formula-box::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #16a34a, #4ade80);
        border-radius: 3px 0 0 3px;
        transform: scaleY(0);
        transform-origin: top;
        transition: transform 0.55s cubic-bezier(0.16,1,0.3,1) 0.18s;
    }
    .formula-box.formula-revealed::before { transform: scaleY(1); }

    .alur-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 5px 0;
    }
    .alur-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #16a34a;
        flex-shrink: 0;
        margin-top: 4px;
        animation: dotPulse 2.5s ease-in-out infinite;
    }
    .alur-dot.red {
        background: #ef4444;
        box-shadow: 0 0 0 3px rgba(239,68,68,0.12);
        animation: none;
    }

    .zakat-type-badge {
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 99px;
        background: rgba(22,163,74,0.10);
        color: #15803d;
        border: 1px solid rgba(22,163,74,0.18);
        margin-bottom: 0.75rem;
    }

    .ketentuan-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
    }
    .ketentuan-table th {
        background: #f0fdf4;
        color: #15803d;
        font-weight: 700;
        padding: 0.5rem 0.75rem;
        text-align: left;
        border-bottom: 1px solid #dcfce7;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .ketentuan-table td {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        vertical-align: top;
        transition: background 0.18s;
    }
    .ketentuan-table tr:last-child td { border-bottom: none; }
    .ketentuan-table tbody tr { opacity: 0; }
    .ketentuan-table tbody tr.row-visible {
        animation: rowSlide 0.42s cubic-bezier(0.16,1,0.3,1) forwards;
    }
    .ketentuan-table tbody tr.row-visible td { opacity: 1; }
    .ketentuan-table tbody tr:hover td { background: #f9fffe; }

    .method-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: box-shadow 0.3s, border-color 0.3s, transform 0.3s;
    }
    .method-card:hover {
        box-shadow: 0 10px 32px rgba(22,163,74,0.10);
        border-color: #bbf7d0;
        transform: translateY(-4px);
    }

    .panduan-nav a.active {
        animation: glowPulse 3s ease-in-out infinite;
    }
    </style>

    {{-- ══ HERO UNDERLINE — injected right after the hero partial renders ══ --}}
    <div class="hero-underline-wrapper">
        <div class="hero-underline"></div>
    </div>

    <div class="w-full px-4 sm:px-10 lg:px-20 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- ══ SIDEBAR ══ --}}
            <aside class="lg:w-60 flex-shrink-0">
                <div class="sidebar-card sticky top-24">
                    <div class="sidebar-header">
                        <p class="text-xs font-semibold text-green-200 uppercase tracking-widest mb-0.5">Daftar Isi</p>
                        <h3 class="text-white font-bold text-sm">Panduan Zakat</h3>
                    </div>
                    <nav class="panduan-nav p-2 space-y-0.5">
                        @php
                            $navItems = [
                                ['id' => 'jenis-zakat',       'label' => 'Jenis Zakat'],
                                ['id' => 'zakat-fitrah',      'label' => 'Zakat Fitrah'],
                                ['id' => 'zakat-mal',         'label' => 'Zakat Mal'],
                                ['id' => 'zakat-profesi',     'label' => 'Zakat Profesi'],
                                ['id' => 'zakat-pertanian',   'label' => 'Zakat Pertanian'],
                                ['id' => 'zakat-ternak',      'label' => 'Zakat Hewan Ternak'],
                                ['id' => 'zakat-perniagaan',  'label' => 'Zakat Perniagaan'],
                                ['id' => 'zakat-rikaz',       'label' => 'Zakat Rikaz'],
                                ['id' => 'fidyah',            'label' => 'Fidyah'],
                                ['id' => 'metode-penerimaan', 'label' => 'Metode Penerimaan'],
                                ['id' => 'metode-pembayaran', 'label' => 'Metode Pembayaran'],
                                ['id' => 'mustahik',          'label' => '8 Golongan Mustahik'],
                            ];
                        @endphp
                        @foreach($navItems as $item)
                            <a href="#{{ $item['id'] }}"
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-gray-600 font-medium">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                    <div class="mx-3 mb-3 p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100">
                        <p class="text-xs text-green-800 font-semibold mb-1">Siap menunaikan zakat?</p>
                        <p class="text-xs text-green-600 mb-3 leading-relaxed">Hitung kewajiban Anda sekarang dengan kalkulator otomatis kami.</p>
                        <a href="{{ route('hitung-zakat') }}"
                           class="flex items-center justify-center gap-1.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition-all shadow-sm hover:shadow-md">
                            Hitung Zakat Sekarang
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </aside>

            {{-- ══ KONTEN UTAMA ══ --}}
            <main class="flex-1 min-w-0 space-y-10">

                {{-- ===== JENIS ZAKAT — section anim: flipInBL ===== --}}
                <section id="jenis-zakat" class="scroll-mt-28 panduan-section" data-section-anim="flipinbl">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Jenis Zakat</h2>
                    </div>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready">
                        Sistem ini melayani delapan jenis kewajiban zakat dan fidyah. Setiap jenis memiliki nisab,
                        kadar, dan cara perhitungan yang berbeda sesuai ketentuan syariat dan BAZNAS.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                            $daftarJenis = [
                                ['nama' => 'Zakat Fitrah',       'anchor' => '#zakat-fitrah',     'desc' => 'Wajib setiap Muslim menjelang Idul Fitri. Besarnya 2,5 kg beras atau Rp 50.000 per jiwa (BAZNAS 2024).'],
                                ['nama' => 'Zakat Mal',          'anchor' => '#zakat-mal',        'desc' => 'Zakat harta emas, perak, tabungan, investasi yang telah mencapai nisab 85 gram emas dan haul 1 tahun. Kadar 2,5%.'],
                                ['nama' => 'Zakat Profesi',      'anchor' => '#zakat-profesi',    'desc' => 'Zakat penghasilan dari gaji atau pekerjaan. Nisab setara 85 gram emas per tahun. Kadar 2,5%.'],
                                ['nama' => 'Zakat Pertanian',    'anchor' => '#zakat-pertanian',  'desc' => 'Zakat hasil panen padi, jagung, gandum. Nisab 652,8 kg. Kadar 10% (air hujan) atau 5% (irigasi).'],
                                ['nama' => 'Zakat Hewan Ternak', 'anchor' => '#zakat-ternak',     'desc' => 'Zakat atas sapi, kambing, unta yang digembalakan bebas. Nisab berbeda per jenis, sudah haul 1 tahun.'],
                                ['nama' => 'Zakat Perniagaan',   'anchor' => '#zakat-perniagaan', 'desc' => 'Zakat atas harta yang diperjualbelikan. Nisab senilai 85 gram emas, kadar 2,5%, haul 1 tahun.'],
                                ['nama' => 'Zakat Rikaz',        'anchor' => '#zakat-rikaz',      'desc' => 'Zakat harta karun / harta temuan. Tidak ada nisab dan haul, kadar 20% (seperlima), wajib segera.'],
                                ['nama' => 'Fidyah',             'anchor' => '#fidyah',           'desc' => 'Kewajiban bagi yang tidak mampu berpuasa secara permanen. Dibayar per hari puasa yang ditinggalkan.'],
                            ];
                        @endphp
                        @foreach($daftarJenis as $i => $jenis)
                            <a href="{{ $jenis['anchor'] }}" class="info-card group flex gap-4 items-start anim-ready" data-delay="{{ $i * 70 }}">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $jenis['nama'] }}</h4>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 group-hover:text-green-500 transition-colors flex-shrink-0 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $jenis['desc'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT FITRAH — section anim: zoomFade ===== --}}
                <section id="zakat-fitrah" class="scroll-mt-28 panduan-section" data-section-anim="zoomfade">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Fitrah</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Wajib per Jiwa</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Wajib ditunaikan sebelum shalat Idul Fitri oleh setiap Muslim yang mampu. Dapat dibayar untuk diri sendiri
                        maupun anggota keluarga yang menjadi tanggungan. Sistem menggunakan konstanta
                        <strong class="text-green-700">Rp 50.000/jiwa</strong> dan <strong class="text-green-700">2,5 kg/jiwa</strong> sesuai ketetapan BAZNAS 2024.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanFitrah = [
                                ['label' => 'Uang per Jiwa',  'nilai' => 'Rp 50.000', 'sub' => 'BAZNAS 2024'],
                                ['label' => 'Beras per Jiwa', 'nilai' => '2,5 kg',    'sub' => 'atau 3,5 liter'],
                                ['label' => 'Batas Waktu',    'nilai' => 'Sebelum Id','sub' => 'sebelum shalat Idul Fitri'],
                            ];
                        @endphp
                        @foreach($ketentuanFitrah as $i => $k)
                            <div class="stat-tile anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <p class="text-xs text-green-600 uppercase tracking-wider font-semibold mb-2">{{ $k['label'] }}</p>
                                <p class="text-xl font-extrabold text-green-800">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Total Fisik = Jumlah Jiwa &times; 2,5 kg
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: 4 jiwa &times; 2,5 kg = 10 kg beras</span><br>
                        Total Uang = Total Fisik &times; Harga Beras/kg
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: 10 kg &times; Rp 14.000 = Rp 140.000</span>
                    </div>
                    <div class="note-box anim-ready" data-delay="600">
                        Jika jumlah yang dibayarkan melebihi kewajiban, selisihnya otomatis dicatat sebagai
                        <strong>infaq sukarela</strong> oleh sistem. Membayar lebih awal lebih afdhal agar amil dapat menyalurkan tepat waktu.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT MAL — section anim: slideBlurRight ===== --}}
                <section id="zakat-mal" class="scroll-mt-28 panduan-section" data-section-anim="slideblur">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Mal (Harta)</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Nisab 85 gr Emas &bull; Haul 1 Tahun &bull; Kadar 2,5%</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Zakat harta atas emas, perak, uang, tabungan, investasi, saham, dan properti investasi.
                        Wajib jika harta bersih telah mencapai nisab dan sudah dimiliki selama satu tahun penuh (haul).
                        Nilai nisab mengikuti harga emas terkini yang diambil otomatis dari database sistem.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanMal = [
                                ['label' => 'Nisab', 'nilai' => '85 gram emas', 'sub' => 'mengikuti harga emas terkini'],
                                ['label' => 'Kadar', 'nilai' => '2,5%',         'sub' => 'dari total harta bersih'],
                                ['label' => 'Haul',  'nilai' => '1 Tahun',      'sub' => 'kepemilikan penuh hijriyah'],
                            ];
                        @endphp
                        @foreach($ketentuanMal as $i => $k)
                            <div class="stat-tile anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <p class="text-xs text-green-600 uppercase tracking-wider font-semibold mb-2">{{ $k['label'] }}</p>
                                <p class="text-xl font-extrabold text-green-800">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Harta Bersih = Total Aset (Emas + Perak + Tabungan + Investasi + Properti) &minus; Hutang Jangka Pendek<br>
                        Zakat = Harta Bersih &times; 2,5%
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: Rp 200.000.000 &times; 2,5% = Rp 5.000.000</span>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 anim-ready" data-delay="600">
                        <table class="ketentuan-table">
                            <thead><tr><th>Komponen Harta</th><th>Keterangan</th></tr></thead>
                            <tbody>
                                <tr><td class="font-medium text-gray-800">Emas &amp; Perhiasan</td><td class="text-gray-500">Dihitung gram &times; harga emas BAZNAS. Perhiasan yang dipakai sehari-hari ada perbedaan pendapat ulama.</td></tr>
                                <tr><td class="font-medium text-gray-800">Perak</td><td class="text-gray-500">Dihitung gram &times; harga perak. Nisab perak 595 gram.</td></tr>
                                <tr><td class="font-medium text-gray-800">Tabungan / Deposito</td><td class="text-gray-500">Saldo rekening bank, termasuk bunga/bagi hasil yang diterima.</td></tr>
                                <tr><td class="font-medium text-gray-800">Investasi / Saham</td><td class="text-gray-500">Reksa dana, saham, obligasi, piutang yang kemungkinan besar tertagih.</td></tr>
                                <tr><td class="font-medium text-gray-800">Properti Investasi</td><td class="text-gray-500">Properti yang diniatkan untuk dijual atau disewakan. Properti hunian sendiri tidak wajib zakat.</td></tr>
                                <tr><td class="font-medium text-gray-800">Hutang Jangka Pendek</td><td class="text-gray-500">Dikurangkan dari total aset. Hanya hutang yang jatuh tempo dalam 1 tahun.</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="note-box anim-ready" data-delay="700">
                        Nisab perak (595 gram) umumnya lebih rendah nilainya dibanding emas. Jika harta tidak mencapai nisab emas
                        namun mencapai nisab perak, sebagian ulama mewajibkan zakat. Konsultasikan dengan amil atau ulama setempat.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT PROFESI — section anim: cascadeDrop ===== --}}
                <section id="zakat-profesi" class="scroll-mt-28 panduan-section" data-section-anim="cascade">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Profesi / Penghasilan</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Nisab 85 gr Emas/Tahun &bull; Tanpa Haul &bull; Kadar 2,5%</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Zakat atas penghasilan dari gaji, honor, fee, atau bentuk imbalan lain dari pekerjaan profesi.
                        Nisab dihitung per bulan (1/12 dari nilai 85 gram emas). Tidak disyaratkan haul — wajib dibayar
                        setiap kali menerima penghasilan jika sudah mencapai nisab.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanProfesi = [
                                ['label' => 'Nisab/Tahun', 'nilai' => '85 gr emas', 'sub'  => 'setara nilai emas terkini'],
                                ['label' => 'Kadar',       'nilai' => '2,5%',       'sub'  => 'dari penghasilan bersih'],
                                ['label' => 'Haul',        'nilai' => 'Tidak Ada',  'sub'  => 'wajib tiap terima gaji'],
                            ];
                        @endphp
                        @foreach($ketentuanProfesi as $i => $k)
                            <div class="stat-tile anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <p class="text-xs text-green-600 uppercase tracking-wider font-semibold mb-2">{{ $k['label'] }}</p>
                                <p class="text-xl font-extrabold text-green-800">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Penghasilan Bersih = (Gaji + Penghasilan Lain) &minus; Kebutuhan Pokok<br>
                        Nisab Bulanan = Nisab Tahunan &divide; 12<br>
                        Zakat = Penghasilan Bersih &times; 2,5%
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: Rp 10.000.000 &times; 2,5% = Rp 250.000/bulan</span>
                    </div>
                    <div class="info-card mb-4 anim-ready" data-delay="600">
                        <h4 class="font-semibold text-gray-800 mb-4 text-sm flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                            Contoh Perhitungan
                        </h4>
                        <div class="space-y-1 text-sm">
                            @php
                                $contohProfesi = [
                                    ['lbl' => 'Gaji per bulan',         'val' => 'Rp 10.000.000'],
                                    ['lbl' => 'Penghasilan lain',       'val' => 'Rp 2.000.000'],
                                    ['lbl' => 'Kebutuhan pokok/cicilan','val' => 'Rp 3.000.000'],
                                    ['lbl' => 'Penghasilan bersih',     'val' => 'Rp 9.000.000'],
                                ];
                            @endphp
                            @foreach($contohProfesi as $row)
                                <div class="flex justify-between items-center py-2 border-b border-gray-50">
                                    <span class="text-gray-500">{{ $row['lbl'] }}</span>
                                    <span class="font-semibold text-gray-800">{{ $row['val'] }}</span>
                                </div>
                            @endforeach
                            <div class="flex justify-between items-center py-3 mt-1 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl px-4">
                                <span class="font-bold text-gray-800">Zakat per bulan (2,5%)</span>
                                <span class="font-extrabold text-green-700 text-base">Rp 225.000</span>
                            </div>
                        </div>
                    </div>
                    <div class="note-box anim-ready" data-delay="700">
                        Kebutuhan pokok yang dapat dikurangkan meliputi: biaya makan, transportasi, cicilan rumah tinggal,
                        biaya pendidikan anak, dan kebutuhan hidup dasar lainnya yang tidak berlebihan.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT PERTANIAN — section anim: earthRise ===== --}}
                <section id="zakat-pertanian" class="scroll-mt-28 panduan-section" data-section-anim="earthrise">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Pertanian</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Nisab 652,8 kg &bull; Tanpa Haul &bull; Kadar 5% atau 10%</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Wajib atas hasil panen berupa padi, jagung, gandum, dan sejenisnya yang dapat dimakan dan disimpan.
                        Kadar zakat berbeda tergantung sumber pengairan. Tidak disyaratkan haul — dikeluarkan langsung saat panen.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanTani = [
                                ['label' => 'Nisab',          'nilai' => '652,8 kg', 'sub' => 'hasil bersih panen'],
                                ['label' => 'Kadar (Hujan)',  'nilai' => '10%',      'sub' => 'air hujan / sungai alami'],
                                ['label' => 'Kadar (Irigasi)','nilai' => '5%',       'sub' => 'irigasi / pompa berbayar'],
                            ];
                        @endphp
                        @foreach($ketentuanTani as $i => $k)
                            <div class="stat-tile anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <p class="text-xs text-green-600 uppercase tracking-wider font-semibold mb-2">{{ $k['label'] }}</p>
                                <p class="text-xl font-extrabold text-green-800">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Nilai Kotor = Hasil Panen (kg) &times; Harga Jual/kg<br>
                        Nilai Bersih = Nilai Kotor &minus; Biaya Produksi<br>
                        Zakat = Nilai Bersih &times; Kadar (5% atau 10%)
                        <span class="text-gray-400 font-sans text-xs ml-2">| Contoh: Rp 10.000.000 &times; 10% = Rp 1.000.000</span>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 anim-ready" data-delay="600">
                        <table class="ketentuan-table">
                            <thead><tr><th>Sumber Air</th><th>Kadar</th><th>Contoh</th></tr></thead>
                            <tbody>
                                <tr><td class="font-medium text-gray-800">Air Hujan / Sungai / Mata Air Alami</td><td><span class="font-bold text-green-700">10%</span></td><td class="text-gray-500">Sawah tadah hujan, pengairan alami dari sungai tanpa biaya</td></tr>
                                <tr><td class="font-medium text-gray-800">Irigasi / Pompa Air / Berbayar</td><td><span class="font-bold text-green-700">5%</span></td><td class="text-gray-500">Sawah dengan irigasi teknis, pompa listrik, atau biaya pengairan</td></tr>
                                <tr><td class="font-medium text-gray-800">Campuran (sebagian alami, sebagian irigasi)</td><td><span class="font-bold text-green-700">7,5%</span></td><td class="text-gray-500">Kombinasi keduanya, kadar diambil rata-rata</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="note-box anim-ready" data-delay="700">
                        Tidak ada haul pada zakat pertanian — wajib dikeluarkan setiap kali panen. Jika dalam satu tahun
                        panen lebih dari sekali, zakat dikeluarkan setiap kali panen mencapai nisab.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT HEWAN TERNAK — section anim: sweepLeft ===== --}}
                <section id="zakat-ternak" class="scroll-mt-28 panduan-section" data-section-anim="sweepleft">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Hewan Ternak</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Nisab Berbeda Per Jenis &bull; Haul 1 Tahun &bull; Kadar 2,5%</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Wajib atas hewan ternak yang digembalakan secara bebas (sa'imah) dan telah mencapai nisab serta
                        haul 1 tahun. Hewan yang digunakan untuk bekerja (membajak, mengangkut barang) tidak termasuk
                        wajib zakat. Pendekatan kontemporer menggunakan kadar 2,5% dari total nilai ternak.
                    </p>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 anim-ready" data-delay="300">
                        <table class="ketentuan-table">
                            <thead><tr><th>Jenis Ternak</th><th>Nisab</th><th>Kewajiban Tradisional</th><th>Pendekatan Kontemporer</th></tr></thead>
                            <tbody>
                                <tr><td class="font-medium text-gray-800">Unta</td><td><span class="font-bold text-green-700">5 ekor</span></td><td class="text-gray-500">5–9 ekor: 1 ekor kambing betina<br>10–14 ekor: 2 ekor kambing</td><td class="text-gray-500">2,5% dari nilai total</td></tr>
                                <tr><td class="font-medium text-gray-800">Sapi / Kerbau</td><td><span class="font-bold text-green-700">30 ekor</span></td><td class="text-gray-500">30–39 ekor: 1 ekor tabi'<br>40–59 ekor: 1 ekor musinnah</td><td class="text-gray-500">2,5% dari nilai total</td></tr>
                                <tr><td class="font-medium text-gray-800">Kambing / Domba</td><td><span class="font-bold text-green-700">40 ekor</span></td><td class="text-gray-500">40–120 ekor: 1 ekor kambing<br>121–200 ekor: 2 ekor kambing</td><td class="text-gray-500">2,5% dari nilai total</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Nilai Total Ternak = Jumlah Ekor &times; Harga Pasar per Ekor<br>
                        Zakat = Nilai Total Ternak &times; 2,5%
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: 30 sapi &times; Rp 15.000.000 = Rp 450.000.000 &times; 2,5% = Rp 11.250.000</span>
                    </div>
                    <div class="note-box anim-ready" data-delay="600">
                        Syarat wajib zakat ternak: (1) Digembalakan bebas sepanjang tahun, (2) Tidak digunakan untuk bekerja,
                        (3) Sudah mencapai nisab, (4) Sudah haul 1 tahun hijriyah.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT PERNIAGAAN — section anim: springPop ===== --}}
                <section id="zakat-perniagaan" class="scroll-mt-28 panduan-section" data-section-anim="springpop">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Perniagaan</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Nisab 85 gr Emas &bull; Haul 1 Tahun &bull; Kadar 2,5%</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Wajib atas harta yang diperjualbelikan dengan tujuan mendapat keuntungan (tijarah).
                        Meliputi stok barang dagangan, kas usaha, dan piutang yang dapat ditagih, dikurangi hutang dagang.
                        Haul dihitung dari awal usaha atau kepemilikan barang.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanPn = [
                                ['label' => 'Nisab',  'nilai' => '85 gr emas',  'sub' => 'senilai harga emas terkini'],
                                ['label' => 'Kadar',  'nilai' => '2,5%',        'sub' => 'dari harta perniagaan bersih'],
                                ['label' => 'Haul',   'nilai' => '1 Tahun',     'sub' => 'dari awal usaha/kepemilikan'],
                            ];
                        @endphp
                        @foreach($ketentuanPn as $i => $k)
                            <div class="stat-tile anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <p class="text-xs text-green-600 uppercase tracking-wider font-semibold mb-2">{{ $k['label'] }}</p>
                                <p class="text-xl font-extrabold text-green-800">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Harta Perniagaan = Stok Barang + Kas/Rekening Usaha + Piutang Lancar<br>
                        Harta Bersih = Harta Perniagaan &minus; Hutang Dagang<br>
                        Zakat = Harta Bersih &times; 2,5%
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 anim-ready" data-delay="600">
                        <table class="ketentuan-table">
                            <thead><tr><th>Komponen</th><th>Masuk Perhitungan</th><th>Keterangan</th></tr></thead>
                            <tbody>
                                <tr><td class="font-medium text-gray-800">Stok barang dagangan</td><td><span class="text-green-600 font-bold">Ya (+)</span></td><td class="text-gray-500">Dinilai berdasarkan harga pasar saat haul</td></tr>
                                <tr><td class="font-medium text-gray-800">Kas dan rekening usaha</td><td><span class="text-green-600 font-bold">Ya (+)</span></td><td class="text-gray-500">Termasuk uang tunai dan saldo bank usaha</td></tr>
                                <tr><td class="font-medium text-gray-800">Piutang yang dapat ditagih</td><td><span class="text-green-600 font-bold">Ya (+)</span></td><td class="text-gray-500">Piutang yang hampir pasti akan tertagih</td></tr>
                                <tr><td class="font-medium text-gray-800">Peralatan / mesin produksi</td><td><span class="text-red-500 font-bold">Tidak</span></td><td class="text-gray-500">Aset tetap operasional tidak termasuk zakat</td></tr>
                                <tr><td class="font-medium text-gray-800">Hutang dagang jatuh tempo</td><td><span class="text-red-500 font-bold">Dikurang (&minus;)</span></td><td class="text-gray-500">Hutang yang jatuh tempo dalam 1 tahun ke depan</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="note-box anim-ready" data-delay="700">
                        Zakat perniagaan mencakup semua jenis usaha: toko fisik, bisnis online, properti yang diperjualbelikan,
                        hingga usaha jasa yang memiliki piutang. Usaha yang baru berjalan kurang dari 1 tahun belum wajib zakat perniagaan.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== ZAKAT RIKAZ — section anim: diagonalReveal ===== --}}
                <section id="zakat-rikaz" class="scroll-mt-28 panduan-section" data-section-anim="diagonal">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Zakat Rikaz (Harta Temuan)</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">Tanpa Nisab &bull; Tanpa Haul &bull; Kadar 20%</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Rikaz adalah harta karun atau harta peninggalan dari zaman sebelum Islam (zaman jahiliyah) yang ditemukan
                        terpendam di dalam tanah. Berbeda dengan luqathah (barang temuan biasa). Tidak ada syarat nisab maupun haul —
                        wajib dizakati langsung saat ditemukan. Dasar: HR. Bukhari &amp; Muslim "Pada rikaz wajib seperlima (khums)."
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $ketentuanRikaz = [
                                ['label' => 'Nisab',  'nilai' => 'Tidak Ada',   'sub' => 'berapapun wajib dizakati'],
                                ['label' => 'Kadar',  'nilai' => '20%',         'sub' => 'seperlima dari nilai harta'],
                                ['label' => 'Haul',   'nilai' => 'Tidak Ada',   'sub' => 'wajib langsung saat ditemukan'],
                            ];
                        @endphp
                        @foreach($ketentuanRikaz as $i => $k)
                            <div class="stat-tile anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <p class="text-xs text-green-600 uppercase tracking-wider font-semibold mb-2">{{ $k['label'] }}</p>
                                <p class="text-xl font-extrabold text-green-800">{{ $k['nilai'] }}</p>
                                <p class="text-xs text-gray-400 mt-1.5">{{ $k['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Zakat = Nilai Harta Temuan &times; 20%
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: Rp 50.000.000 &times; 20% = Rp 10.000.000</span><br>
                        Sisa milik penemu = Nilai Harta &times; 80%
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 anim-ready" data-delay="600">
                        <table class="ketentuan-table">
                            <thead><tr><th>Jenis Harta</th><th>Cara Hitung</th></tr></thead>
                            <tbody>
                                <tr><td class="font-medium text-gray-800">Emas temuan</td><td class="text-gray-500">Berat (gram) &times; harga emas BAZNAS &times; 20%</td></tr>
                                <tr><td class="font-medium text-gray-800">Uang / Logam kuno</td><td class="text-gray-500">Nilai pasar / nilai taksiran &times; 20%</td></tr>
                                <tr><td class="font-medium text-gray-800">Barang berharga lainnya</td><td class="text-gray-500">Estimasi nilai pasar wajar &times; 20%</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="note-box-amber anim-ready" data-delay="700">
                        Rikaz berbeda dengan luqathah (barang temuan pemiliknya tidak diketahui). Rikaz adalah harta peninggalan
                        zaman jahiliyah. Konsultasikan dengan ulama untuk penentuan status harta temuan Anda sebelum membayar zakat.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== FIDYAH — section anim: softDrift ===== --}}
                <section id="fidyah" class="scroll-mt-28 panduan-section" data-section-anim="softdrift">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Fidyah</h2>
                    </div>
                    <span class="zakat-type-badge anim-ready" data-delay="100">675 gram/hari &bull; Per Hari Puasa Ditinggalkan</span>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="150">
                        Kewajiban bagi yang tidak mampu berpuasa Ramadhan secara permanen (sakit kronis, lansia yang sudah sangat lemah).
                        Bukan untuk yang sakit sementara — mereka wajib qadha. Sistem menggunakan konstanta
                        <strong class="text-green-700">675 gram/hari</strong> sesuai BAZNAS 2024.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        @php
                            $tipesFidyah = [
                                ['tipe' => 'Bahan Mentah',   'nilai' => '675 gram/hari',   'desc' => 'Serahkan bahan makanan pokok secara fisik. Total berat dihitung dari 675 gram dikali jumlah hari.'],
                                ['tipe' => 'Makanan Matang', 'nilai' => '1 porsi/hari',    'desc' => 'Isi menu, jumlah porsi, harga per porsi, dan cara serah: langsung dibagikan, dijamu, atau via lembaga.'],
                                ['tipe' => 'Uang Tunai',     'nilai' => 'Nominal x hari',  'desc' => 'Bayar tunai, transfer, atau QRIS. Total dihitung dari harga fidyah per hari dikali jumlah hari puasa yang ditinggalkan.'],
                            ];
                        @endphp
                        @foreach($tipesFidyah as $i => $tipe)
                            <div class="info-card text-center anim-ready" data-delay="{{ 200 + $i * 100 }}">
                                <h4 class="font-bold text-gray-900 text-sm mb-1.5">{{ $tipe['tipe'] }}</h4>
                                <p class="text-sm font-extrabold text-green-600 mb-3">{{ $tipe['nilai'] }}</p>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $tipe['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="formula-box mb-4 anim-ready" data-delay="500">
                        Total Bahan Mentah = 675 gram &times; Jumlah Hari Puasa Ditinggalkan<br>
                        Total Uang = Harga Fidyah/Hari &times; Jumlah Hari Puasa Ditinggalkan
                        <span class="text-gray-400 font-sans text-xs ml-3">Contoh: Rp 14.000 &times; 30 hari = Rp 420.000</span>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4 anim-ready" data-delay="600">
                        <table class="ketentuan-table">
                            <thead><tr><th>Yang Wajib Fidyah</th><th>Yang Tidak Wajib Fidyah</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td class="text-gray-600 leading-relaxed">
                                        Orang sakit yang tidak ada harapan sembuh<br>
                                        Lansia yang tidak mampu berpuasa<br>
                                        Ibu hamil/menyusui (menurut sebagian ulama, ditambah qadha)
                                    </td>
                                    <td class="text-gray-600 leading-relaxed">
                                        Sakit yang masih bisa sembuh (wajib qadha)<br>
                                        Musafir dalam perjalanan (wajib qadha)<br>
                                        Haid / nifas (wajib qadha)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="note-box anim-ready" data-delay="700">
                        Pilih <strong>Jenis: Fidyah</strong> saat mengisi formulir, kemudian isi jumlah hari puasa
                        yang ditinggalkan dan pilih tipe fidyah yang sesuai. Fidyah dibayarkan kepada fakir miskin.
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== METODE PENERIMAAN — section anim: spreadIn ===== --}}
                <section id="metode-penerimaan" class="scroll-mt-28 panduan-section" data-section-anim="spreadin">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Metode Penerimaan</h2>
                    </div>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="100">
                        Tersedia tiga metode penerimaan zakat. Masing-masing memiliki alur status yang berbeda.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $metodeCards = [
                                [
                                    'judul'    => 'Datang Langsung',
                                    'sub'      => 'Muzaki hadir ke masjid',
                                    'desc'     => 'Muzaki datang ke masjid dan menyerahkan zakat langsung kepada amil. Amil menginput transaksi, kwitansi langsung diterbitkan.',
                                    'alurType' => 'pill',
                                    'alur'     => ['Menunggu', 'Terverifikasi'],
                                    'note'     => 'Tunai dan beras otomatis terverifikasi. Transfer dan QRIS perlu konfirmasi amil.',
                                ],
                                [
                                    'judul'    => 'Dijemput Amil',
                                    'sub'      => 'Amil datang ke lokasi',
                                    'desc'     => 'Muzaki mengajukan penjemputan. Amil datang ke lokasi muzaki untuk mengambil zakat.',
                                    'alurType' => 'dot',
                                    'alur'     => ['Menunggu' => 'Request masuk', 'Diterima' => 'Amil konfirmasi', 'Dalam Perjalanan' => 'Amil berangkat', 'Sampai Lokasi' => 'Amil tiba', 'Selesai' => 'Zakat diterima'],
                                    'note'     => '',
                                ],
                                [
                                    'judul'    => 'Daring (Online)',
                                    'sub'      => 'Formulir & bukti digital',
                                    'desc'     => 'Muzaki isi formulir online, transfer atau scan QRIS, lalu upload bukti. Amil mengkonfirmasi setelah bukti diterima.',
                                    'alurType' => 'dot',
                                    'alur'     => ['Menunggu Konfirmasi' => 'Menunggu cek amil', 'Dikonfirmasi' => 'Bukti valid, lunas', 'Ditolak' => 'Bukti tidak valid'],
                                    'note'     => '',
                                ],
                            ];
                        @endphp
                        @foreach($metodeCards as $i => $mc)
                            <div class="method-card anim-ready" data-delay="{{ 150 + $i * 120 }}">
                                <div class="method-header">
                                    <p class="text-white font-bold text-sm">{{ $mc['judul'] }}</p>
                                    <p class="text-green-200 text-xs mt-0.5">{{ $mc['sub'] }}</p>
                                </div>
                                <div class="p-4">
                                    <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ $mc['desc'] }}</p>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Alur Status</p>
                                    @if($mc['alurType'] === 'pill')
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="status-pill">Menunggu</span>
                                            <span class="text-gray-300 text-xs">→</span>
                                            <span class="status-pill" style="background:#dcfce7;color:#15803d;border-color:rgba(22,163,74,0.28);">Terverifikasi</span>
                                        </div>
                                        <p class="text-xs text-gray-400 mt-3 leading-relaxed">{{ $mc['note'] }}</p>
                                    @else
                                        <div class="space-y-2">
                                            @foreach($mc['alur'] as $status => $ket)
                                                <div class="alur-item">
                                                    <div class="alur-dot {{ $status === 'Ditolak' ? 'red' : '' }}"></div>
                                                    <div>
                                                        <span class="text-xs font-semibold text-gray-700">{{ $status }}</span>
                                                        <span class="text-xs text-gray-400 ml-1">— {{ $ket }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== METODE PEMBAYARAN — section anim: waveIn ===== --}}
                <section id="metode-pembayaran" class="scroll-mt-28 panduan-section" data-section-anim="wavein">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">Metode Pembayaran</h2>
                    </div>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="100">
                        Tersedia enam metode pembayaran. Ketersediaannya bergantung pada jenis zakat dan metode penerimaan yang dipilih.
                    </p>
                    <div class="space-y-3 mb-6">
                        @php
                            $metodeBayar = [
                                ['nama' => 'Tunai',          'catatan' => 'Otomatis terverifikasi',  'cat_cls' => 'bg-green-100 text-green-700',   'desc' => 'Bayar cash langsung kepada amil. Transaksi otomatis terverifikasi tanpa konfirmasi tambahan.',                                         'tersedia' => ['Datang Langsung', 'Dijemput Amil']],
                                ['nama' => 'Transfer Bank',  'catatan' => 'Perlu konfirmasi amil',   'cat_cls' => 'bg-amber-100 text-amber-700',   'desc' => 'Transfer ke rekening masjid, kemudian upload foto bukti transfer. Amil akan mengecek dan mengkonfirmasi.',                         'tersedia' => ['Datang Langsung', 'Dijemput Amil', 'Daring']],
                                ['nama' => 'QRIS',           'catatan' => 'Perlu konfirmasi amil',   'cat_cls' => 'bg-amber-100 text-amber-700',   'desc' => 'Scan kode QRIS masjid via dompet digital atau mobile banking. Upload screenshot bukti, amil mengkonfirmasi.',                    'tersedia' => ['Datang Langsung', 'Dijemput Amil', 'Daring']],
                                ['nama' => 'Beras',          'catatan' => 'Khusus Zakat Fitrah',     'cat_cls' => 'bg-blue-100 text-blue-700',     'desc' => 'Serahkan beras fisik kepada amil. Hanya jumlah kilogram yang dicatat, otomatis terverifikasi.',                                   'tersedia' => ['Datang Langsung', 'Dijemput Amil']],
                                ['nama' => 'Bahan Mentah',   'catatan' => 'Khusus Fidyah',           'cat_cls' => 'bg-purple-100 text-purple-700', 'desc' => 'Serahkan bahan makanan pokok secara fisik. Total berat = 675 gram x jumlah hari puasa yang ditinggalkan.',                       'tersedia' => ['Datang Langsung', 'Dijemput Amil']],
                                ['nama' => 'Makanan Matang', 'catatan' => 'Khusus Fidyah',           'cat_cls' => 'bg-purple-100 text-purple-700', 'desc' => 'Isi detail menu, jumlah porsi, harga per porsi, dan cara penyerahan: langsung dibagikan, dijamu, atau via lembaga.',            'tersedia' => ['Datang Langsung', 'Dijemput Amil']],
                            ];
                        @endphp
                        @foreach($metodeBayar as $i => $m)
                            <div class="info-card flex gap-4 items-start anim-ready" data-delay="{{ $i * 80 }}">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $m['nama'] }}</h4>
                                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $m['cat_cls'] }}">{{ $m['catatan'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed mb-2.5">{{ $m['desc'] }}</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($m['tersedia'] as $t)
                                            <span class="text-xs border border-green-100 bg-green-50 text-green-700 px-2.5 py-0.5 rounded-full font-medium">{{ $t }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 anim-ready" data-delay="500">
                        <h4 class="font-bold text-gray-800 mb-4 text-sm flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                            Status Transaksi
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @php
                                $statusList = [
                                    ['kode' => 'Menunggu',      'dot' => '#9ca3af', 'ket' => 'Transaksi masuk, belum terverifikasi. Berlaku untuk transfer dan QRIS yang menunggu konfirmasi amil.'],
                                    ['kode' => 'Terverifikasi', 'dot' => '#16a34a', 'ket' => 'Transaksi sah. Tunai otomatis terverifikasi. Transfer dan QRIS diverifikasi setelah dikonfirmasi amil.'],
                                    ['kode' => 'Ditolak',       'dot' => '#ef4444', 'ket' => 'Ditolak amil. Contohnya bukti transfer tidak valid, nominal tidak sesuai, atau foto tidak terbaca.'],
                                ];
                            @endphp
                            @foreach($statusList as $i => $s)
                                <div class="bg-white border border-gray-200 rounded-xl p-3.5 anim-ready" data-delay="{{ 550 + $i * 80 }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $s['dot'] }}"></div>
                                        <span class="text-sm font-bold text-gray-800">{{ $s['kode'] }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $s['ket'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <hr class="section-divider">

                {{-- ===== 8 GOLONGAN MUSTAHIK — section anim: badgeBurst ===== --}}
                <section id="mustahik" class="scroll-mt-28 panduan-section" data-section-anim="badgeburst">
                    <div class="section-heading">
                        <div class="section-heading-bar"></div>
                        <h2 class="text-xl font-bold text-gray-900 anim-ready">8 Golongan Penerima Zakat</h2>
                    </div>
                    <p class="text-gray-500 leading-relaxed mb-5 text-sm anim-ready" data-delay="100">
                        Allah SWT menetapkan dalam QS. At-Taubah: 60 bahwa zakat hanya boleh disalurkan kepada delapan golongan berikut.
                        Lembaga mendistribusikan zakat sesuai program penyaluran yang aktif.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @php
                            $mustahik = [
                                ['no' => '1', 'nama' => 'Fakir',         'ket' => 'Tidak memiliki harta sama sekali atau sangat sedikit sehingga tidak mampu memenuhi kebutuhan pokok.'],
                                ['no' => '2', 'nama' => 'Miskin',        'ket' => 'Memiliki penghasilan namun belum cukup untuk memenuhi kebutuhan pokok sehari-hari secara layak.'],
                                ['no' => '3', 'nama' => 'Amil',          'ket' => 'Orang atau lembaga yang ditugaskan mengumpulkan, mengelola, dan mendistribusikan zakat kepada yang berhak.'],
                                ['no' => '4', 'nama' => 'Muallaf',       'ket' => 'Orang yang baru masuk Islam atau yang sedang dirangkul hatinya agar semakin dekat dengan Islam.'],
                                ['no' => '5', 'nama' => 'Riqab',         'ket' => 'Hamba sahaya yang ingin merdeka. Kini dimaknai sebagai membantu orang yang terbelenggu kemiskinan struktural.'],
                                ['no' => '6', 'nama' => 'Gharim',        'ket' => 'Orang yang terlilit utang bukan karena kemaksiatan dan tidak mampu melunasinya.'],
                                ['no' => '7', 'nama' => 'Fi Sabilillah', 'ket' => 'Orang yang berjuang di jalan Allah, termasuk pendidik agama, ulama, dan pejuang kepentingan Islam.'],
                                ['no' => '8', 'nama' => 'Ibnu Sabil',    'ket' => 'Musafir yang kehabisan bekal di perjalanan, meskipun di daerah asalnya tergolong berkecukupan.'],
                            ];
                        @endphp
                        @foreach($mustahik as $i => $item)
                            <div class="info-card flex gap-4 items-start anim-ready" data-delay="{{ $i * 70 }}">
                                <div class="step-badge">{{ $item['no'] }}</div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 text-sm mb-1">{{ $item['nama'] }}</p>
                                    <p class="text-xs text-gray-500 leading-relaxed">{{ $item['ket'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- CTA --}}
                <div class="cta-block anim-ready" data-anim="floatin" data-delay="0">
                    <div class="relative z-10">
                        <h3 class="text-lg font-bold text-white mb-2">Tunaikan Zakat Sekarang</h3>
                        <p class="text-green-100 text-sm mb-6 max-w-md mx-auto leading-relaxed">
                            Pilih metode penerimaan yang paling mudah bagi Anda. Zakat langsung tersalurkan kepada yang berhak.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('hitung-zakat') }}"
                               class="inline-flex items-center justify-center gap-2 bg-white text-green-700 font-bold px-6 py-3 rounded-xl hover:bg-green-50 transition-all shadow-sm hover:shadow-md text-sm">
                                Hitung Zakat Saya
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center justify-center gap-2 bg-white/15 hover:bg-white/25 border border-white/30 text-white font-bold px-6 py-3 rounded-xl transition-all text-sm backdrop-blur-sm">
                                Daftar dan Bayar Zakat
                            </a>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
    (function () {

        /* ═══════════════════════════════════════════════════════════
           ANIMATION MAP — each section has its own entrance style
        ═══════════════════════════════════════════════════════════ */
        var SECTION_ANIM_MAP = {
            'flipinbl'   : 'anim-flipinbl',
            'zoomfade'   : 'anim-zoomfade',
            'slideblur'  : 'anim-slideblur',
            'cascade'    : 'anim-cascade',
            'earthrise'  : 'anim-earthrise',
            'sweepleft'  : 'anim-sweepleft',
            'springpop'  : 'anim-springpop',
            'diagonal'   : 'anim-diagonal',
            'softdrift'  : 'anim-softdrift',
            'spreadin'   : 'anim-spreadin',
            'wavein'     : 'anim-wavein',
            'badgeburst' : 'anim-badgeburst',
        };

        /* Fallback generic map (for elements that override via data-anim) */
        var GENERIC_ANIM_MAP = {
            'fadeup'    : 'anim-fadeup',
            'fadedown'  : 'anim-fadedown',
            'fadeleft'  : 'anim-fadeleft',
            'faderight' : 'anim-faderight',
            'scalepop'  : 'anim-scalepop',
            'badgepop'  : 'anim-badgepop',
            'floatin'   : 'anim-floatin',
            'rowslide'  : 'anim-rowslide',
        };

        function animateSection(section) {
            var sectionAnimKey = section.dataset.sectionAnim || 'fadeup';
            var sectionAnimCls = SECTION_ANIM_MAP[sectionAnimKey] || 'anim-fadeup';

            /* 1. Heading bar grow */
            var bar = section.querySelector('.section-heading-bar');
            if (bar) {
                setTimeout(function () { bar.classList.add('bar-grown'); }, 60);
            }

            /* 2. Formula box left line */
            var formulas = section.querySelectorAll('.formula-box');
            formulas.forEach(function (f) {
                setTimeout(function () { f.classList.add('formula-revealed'); }, 320);
            });

            /* 3. Table rows stagger */
            var tables = section.querySelectorAll('.ketentuan-table tbody');
            tables.forEach(function (tbody) {
                var rows = tbody.querySelectorAll('tr');
                rows.forEach(function (row, i) {
                    setTimeout(function () {
                        row.classList.add('row-visible');
                    }, 420 + i * 85);
                });
            });

            /* 4. .anim-ready elements — use section's animation unless element has data-anim override */
            var els = section.querySelectorAll('.anim-ready');
            els.forEach(function (el) {
                var overrideKey = el.dataset.anim;
                var animCls = overrideKey
                    ? (GENERIC_ANIM_MAP[overrideKey] || sectionAnimCls)
                    : sectionAnimCls;
                var delay = parseInt(el.dataset.delay || 0, 10);

                setTimeout(function () {
                    el.classList.add(animCls);
                }, delay);
            });
        }

        /* ── CTA block (outside .panduan-section) ─────────────── */
        function animateCTA(el) {
            var delay = parseInt(el.dataset.delay || 0, 10);
            setTimeout(function () { el.classList.add('anim-floatin'); }, delay);
        }

        /* ── IntersectionObserver per section ─────────────────── */
        var sections = document.querySelectorAll('.panduan-section');
        var ctaBlocks = document.querySelectorAll('.cta-block.anim-ready');

        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        if (entry.target.classList.contains('panduan-section')) {
                            animateSection(entry.target);
                        } else {
                            animateCTA(entry.target);
                        }
                        obs.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '0px 0px -36px 0px' });

            sections.forEach(function (s) { obs.observe(s); });
            ctaBlocks.forEach(function (c) { obs.observe(c); });
        } else {
            sections.forEach(function (s) { animateSection(s); });
            ctaBlocks.forEach(function (c) { animateCTA(c); });
        }

        /* ── Active nav highlight ─────────────────────────────── */
        var navLinks   = document.querySelectorAll('.panduan-nav a');
        var sectionEls = Array.from(navLinks).map(function (a) {
            return document.querySelector(a.getAttribute('href'));
        });

        function setActive() {
            var scrollY = window.scrollY + 150;
            var current = 0;
            sectionEls.forEach(function (s, i) {
                if (s && s.offsetTop <= scrollY) current = i;
            });
            navLinks.forEach(function (a, i) {
                a.classList.toggle('active', i === current);
            });
        }

        window.addEventListener('scroll', setActive, { passive: true });
        setActive();

        /* ── Smooth scroll ────────────────────────────────────── */
        navLinks.forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                var target = document.querySelector(a.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

    })();
    </script>

@endsection