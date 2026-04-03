{{-- resources/views/partials/landing/sections/laporan.blade.php --}}
@php
    $hasData = isset($laporanPublished) && $laporanPublished->count() > 0;
@endphp

<style>
    /* ── WRAPPER SECTION ─────────────────────────── */
    .lrk-landing-section {
        background: #ffffff;
        padding: 4rem 0;
        position: relative;
        overflow: hidden;
    }

    /* ── ANIMATED UNDERLINE (sama persis dengan cara-kerja) ── */
    .lrk-underline-path {
        fill: none;
        stroke: #16a34a;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-dasharray: 280;
        stroke-dashoffset: 280;
    }

    .lrk-underline-path.lrk-draw {
        animation: lrkDrawLine 1.1s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
    }

    @keyframes lrkDrawLine {
        from { stroke-dashoffset: 280; }
        to   { stroke-dashoffset: 0;   }
    }

    /* ── GRID 3 KOLOM ───────────────────────────── */
    .lrk-landing-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.75rem;
        margin-bottom: 2.5rem;
    }

    @media (min-width: 640px) {
        .lrk-landing-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (min-width: 1024px) {
        .lrk-landing-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* ── CARD ───────────────────────────────────── */
    .lrk-landing-card {
        background: #ffffff;
        border: 1.5px solid #e5e7eb;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform .22s, box-shadow .22s, border-color .22s;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        height: 100%;
    }

    .lrk-landing-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 28px rgba(22,163,74,0.13);
        border-color: #86efac;
    }

    .lrk-landing-card__head { padding: 1.25rem 1.25rem 0.75rem; }

    .lrk-landing-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .lrk-landing-card__kode {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #9ca3af;
    }

    .lrk-landing-card__badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.75rem;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 99px;
        font-size: 0.68rem;
        font-weight: 700;
        color: #16a34a;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .lrk-landing-card__badge-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #22c55e;
        animation: pulseLanding 2.4s ease-in-out infinite;
    }

    @keyframes pulseLanding {
        0%,100% { opacity:1; transform:scale(1); }
        50%      { opacity:.5; transform:scale(0.9); }
    }

    .lrk-landing-card__nama {
        font-size: 1.05rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.35;
        margin: 0 0 0.3rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.2s;
    }

    .lrk-landing-card:hover .lrk-landing-card__nama { color: #16a34a; }

    .lrk-landing-card__kota {
        font-size: 0.7rem;
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .lrk-landing-card__body {
        padding: 0 1.25rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        flex: 1;
    }

    .lrk-landing-divider { height: 1px; background: #f3f4f6; }

    .lrk-landing-stat-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem 1rem;
    }

    .lrk-landing-stat__label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #9ca3af;
        margin-bottom: 0.25rem;
    }

    .lrk-landing-stat__val { font-size: 0.95rem; font-weight: 800; color: #111827; }
    .v-green { color: #16a34a; }
    .v-red   { color: #dc2626; }

    .lrk-landing-rasio-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
    }

    .lrk-landing-rasio-num {
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
        letter-spacing: -0.03em;
    }

    .lrk-landing-rasio-num sup { font-size: 0.85rem; font-weight: 700; vertical-align: super; }
    .rn-high { color: #16a34a; }
    .rn-mid  { color: #f59e0b; }
    .rn-low  { color: #d1d5db; }

    .lrk-landing-rasio-lbl {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #9ca3af;
        margin-top: 0.25rem;
    }

    .lrk-landing-bar { height: 4px; background: #f3f4f6; border-radius: 99px; overflow: hidden; }
    .lrk-landing-bar__fill { height: 100%; border-radius: 99px; transition: width 0.5s cubic-bezier(0.16,1,0.3,1); }
    .bf-high { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .bf-mid  { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .bf-low  { background: #d1d5db; }

    .lrk-landing-people { display: flex; gap: 1.5rem; }
    .lrk-landing-people__num { font-size: 1rem; font-weight: 800; color: #111827; }
    .lrk-landing-people__lbl {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #9ca3af;
        margin-top: 0.15rem;
    }

    .lrk-landing-card__footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.25rem;
        background: #fafafa;
        border-top: 1px solid #f3f4f6;
    }

    .lrk-landing-card__date { font-size: 0.72rem; color: #9ca3af; }

    .lrk-landing-link {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #16a34a;
        text-decoration: none;
        transition: all 0.2s;
    }

    .lrk-landing-link svg { transition: transform 0.2s; }
    .lrk-landing-link:hover { gap: 0.5rem; }
    .lrk-landing-link:hover svg { transform: translateX(3px); }

    .lrk-landing-empty { text-align: center; padding: 4rem 1rem; color: #9ca3af; }
    .lrk-landing-empty svg { margin: 0 auto 1rem; display: block; }
    .lrk-landing-empty p { font-size: 0.95rem; }

    .lrk-landing-cta { text-align: center; margin-top: 1rem; }

    .lrk-landing-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 2rem;
        background: #16a34a;
        color: white;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 99px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(22,163,74,0.2);
    }

    .lrk-landing-cta-btn:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(22,163,74,0.3);
    }
</style>

<section id="laporan-landing" class="lrk-landing-section">
    {{-- Dekorasi background --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-green-100/50 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-16 w-72 h-72 bg-emerald-100/40 rounded-full blur-2xl"></div>
    </div>

    <div class="relative w-full px-4 sm:px-10 lg:px-20">
        {{-- ── Header ────────────────────────────────────── --}}
        <div class="text-center mb-12 nz-reveal">
            <div class="inline-flex items-center gap-2 bg-green-50 border border-green-200/70 rounded-full px-4 py-1.5 text-sm font-medium text-green-700 mb-4">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Laporan Keuangan
            </div>

            {{-- Heading dengan animasi underline berjalan saat di-scroll --}}
            <h2 class="text-3xl lg:text-4xl font-bold text-neutral-900 mb-3 tracking-tight">
                Transparansi Keuangan
                <span class="relative inline-block text-green-600">
                    Lembaga Zakat
                    {{-- SVG underline — stroke-dashoffset di-animate via JS saat masuk viewport --}}
                    <svg class="block w-full overflow-visible" style="height:11px; margin-top:3px;"
                         viewBox="0 0 260 11" preserveAspectRatio="none">
                        <path class="lrk-underline-path" id="lrkUnderlinePath"
                              d="M2,7 Q65,2 130,7 Q195,12 258,6" />
                    </svg>
                </span>
            </h2>

            <p class="text-neutral-500 text-base max-w-xl mx-auto">
                Laporan keuangan resmi yang telah dipublikasikan oleh lembaga-lembaga zakat terdaftar — terbuka dan dapat diakses oleh siapa saja.
            </p>
        </div>

        @if($hasData)
            {{-- ── Grid Card Laporan ──────────────────────── --}}
            <div class="lrk-landing-grid">
                @foreach($laporanPublished->take(3) as $laporan)
                    @php
                        $bulanNama = [
                            1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',
                            7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des',
                        ][$laporan->bulan] ?? '-';

                        $rasio = $laporan->total_penerimaan > 0
                            ? min(round(($laporan->total_penyaluran / $laporan->total_penerimaan) * 100), 100)
                            : 0;
                        $rc = $rasio >= 80 ? 'high' : ($rasio >= 50 ? 'mid' : 'low');

                        $lembagaNama = $laporan->lembaga->nama ?? 'Lembaga';
                        $lembagaKode = $laporan->lembaga->kode_lembaga ?? null;
                        $kota        = $laporan->lembaga->kota_nama ?? null;
                        $publishedAt = $laporan->published_at
                            ? \Carbon\Carbon::parse($laporan->published_at)->isoFormat('D MMM YYYY')
                            : '-';
                    @endphp

                    <article class="lrk-landing-card">
                        <div class="lrk-landing-card__head">
                            <div class="lrk-landing-card__top">
                                <span class="lrk-landing-card__kode">
                                    @if($lembagaKode){{ $lembagaKode }} &middot; @endif{{ $bulanNama }} {{ $laporan->tahun }}
                                </span>
                                <span class="lrk-landing-card__badge">
                                    <span class="lrk-landing-card__badge-dot"></span>
                                    Publik
                                </span>
                            </div>
                            <h3 class="lrk-landing-card__nama">{{ $lembagaNama }}</h3>
                            @if($kota)
                                <div class="lrk-landing-card__kota">{{ $kota }}</div>
                            @endif
                        </div>

                        <div class="lrk-landing-card__body">
                            <div class="lrk-landing-divider"></div>

                            <div class="lrk-landing-stat-row">
                                <div>
                                    <div class="lrk-landing-stat__label">Penerimaan</div>
                                    <div class="lrk-landing-stat__val v-green">Rp {{ number_format($laporan->total_penerimaan, 0, ',', '.') }}</div>
                                </div>
                                <div>
                                    <div class="lrk-landing-stat__label">Penyaluran</div>
                                    <div class="lrk-landing-stat__val">Rp {{ number_format($laporan->total_penyaluran, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="lrk-landing-rasio-row">
                                <div>
                                    <div class="lrk-landing-stat__label">Saldo Akhir</div>
                                    <div class="lrk-landing-stat__val {{ $laporan->saldo_akhir < 0 ? 'v-red' : '' }}">
                                        Rp {{ number_format($laporan->saldo_akhir, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div style="text-align:right;">
                                    <div class="lrk-landing-rasio-num rn-{{ $rc }}">{{ $rasio }}<sup>%</sup></div>
                                    <div class="lrk-landing-rasio-lbl">Rasio Salur</div>
                                </div>
                            </div>

                            <div class="lrk-landing-bar">
                                <div class="lrk-landing-bar__fill bf-{{ $rc }}" style="width:{{ $rasio }}%"></div>
                            </div>

                            <div class="lrk-landing-people">
                                <div>
                                    <div class="lrk-landing-people__num">{{ number_format($laporan->jumlah_muzakki) }}</div>
                                    <div class="lrk-landing-people__lbl">Muzakki</div>
                                </div>
                                <div>
                                    <div class="lrk-landing-people__num">{{ number_format($laporan->jumlah_mustahik) }}</div>
                                    <div class="lrk-landing-people__lbl">Mustahik</div>
                                </div>
                            </div>
                        </div>

                        <div class="lrk-landing-card__footer">
                            <span class="lrk-landing-card__date">Terbit {{ $publishedAt }}</span>
                            @if($laporan->lembaga)
                                <a href="{{ route('laporan.detail', $laporan->lembaga->id) }}" class="lrk-landing-link">
                                    Lihat detail
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="lrk-landing-cta">
                <a href="{{ route('laporan.index') }}" class="lrk-landing-cta-btn">
                    Lihat Semua Laporan
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @else
            <div class="lrk-landing-empty">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="56" height="56" rx="16" fill="#f0fdf4" />
                    <path d="M18 20h20M18 28h14M18 36h8" stroke="#86efac" stroke-width="2.5" stroke-linecap="round" />
                </svg>
                <p>Belum ada laporan keuangan yang dipublikasikan.</p>
                <p class="text-sm text-gray-400 mt-2">Laporan akan muncul setelah dipublikasikan oleh admin.</p>
            </div>
        @endif
    </div>
</section>

<script>
(function () {
    var drawn = false;
    var path  = document.getElementById('lrkUnderlinePath');
    if (!path) return;

    function draw() {
        if (drawn) return;
        drawn = true;
        path.classList.add('lrk-draw');
    }

    // Trigger saat section masuk viewport
    var section = document.getElementById('laporan-landing');
    if (!section) return;

    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    draw();
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.25 });
        obs.observe(section);
    } else {
        // Fallback: langsung draw jika IO tidak tersedia
        draw();
    }
})();
</script>