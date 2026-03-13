{{-- ============================================================
     SECTION: STATISTIK — Clean White BG + Mini Donut Charts
     ============================================================ --}}

@php
    $fmtStat = function(int $n): string {
        if ($n >= 1_000_000) return number_format($n / 1_000_000, 1) . 'M';
        if ($n >= 1_000)     return number_format($n / 1_000, 0) . 'K+';
        return (string) $n;
    };
    $fmtDana = function(float $n): string {
        if ($n >= 1_000_000_000) return number_format($n / 1_000_000_000, 1) . 'M';
        if ($n >= 1_000_000)     return number_format($n / 1_000_000, 0) . 'M';
        if ($n >= 1_000)         return number_format($n / 1_000, 0) . 'K';
        return number_format($n, 0);
    };
    $statLembaga  = $fmtStat((int) ($totalLembaga ?? 0));
    $statMuzaki   = $fmtStat((int) ($totalMuzaki ?? 0));
    $statMustahik = $fmtStat((int) ($totalMustahik ?? 0));
    $statDana     = $fmtDana((float) ($totalDana ?? 0));
    $statProgram  = $fmtStat((int) ($totalProgram ?? 0));
    $rawMuzaki    = max((int)($totalMuzaki ?? 1), 1);
    $rawMustahik  = max((int)($totalMustahik ?? 1), 1);
    $rawProgram   = max((int)($totalProgram ?? 1), 1);
    $rawDana      = max((float)($totalDana ?? 1), 1);
    $total4       = $rawMuzaki + $rawMustahik + $rawProgram + ($rawDana / 1_000_000);
    $pMuzaki      = round($rawMuzaki / $total4 * 100, 1);
    $pMustahik    = round($rawMustahik / $total4 * 100, 1);
    $pProgram     = round($rawProgram / $total4 * 100, 1);
    $pDana        = round(100 - $pMuzaki - $pMustahik - $pProgram, 1);
@endphp

<style>
/* ── Reveal animasi ── */
.stat-reveal {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.9s cubic-bezier(0.16,1,0.3,1),
                transform 0.9s cubic-bezier(0.16,1,0.3,1);
}
.stat-reveal.is-visible { opacity: 1; transform: translateY(0); }

/* ── Underline draw ── */
.stat-underline-path {
    fill: none;
    stroke: #16a34a;
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-dasharray: 260;
    stroke-dashoffset: 260;
}
.stat-underline-path.draw {
    animation: statULDraw 1.2s cubic-bezier(0.4,0,0.2,1) 0.3s forwards;
}
@keyframes statULDraw {
    from { stroke-dashoffset: 260; }
    to   { stroke-dashoffset: 0; }
}

/* ── Main donut wrapper ── */
.stat-donut-outer {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    filter: drop-shadow(0 8px 24px rgba(22,163,74,0.10));
    opacity: 0;
    transform: translateY(20px) scale(0.96);
    transition: opacity 1.1s cubic-bezier(0.16,1,0.3,1),
                transform 1.1s cubic-bezier(0.16,1,0.3,1),
                filter 1.1s ease;
}
.stat-donut-outer.donut-visible {
    opacity: 1;
    transform: translateY(0) scale(1);
    filter: drop-shadow(0 12px 36px rgba(22,163,74,0.13));
}

/* Gentle breathing glow setelah muncul */
@keyframes softGlow {
    0%, 100% { filter: drop-shadow(0 12px 36px rgba(22,163,74,0.10)); }
    50%       { filter: drop-shadow(0 14px 44px rgba(22,163,74,0.22)); }
}
.stat-donut-outer.donut-breathing {
    animation: softGlow 4s ease-in-out infinite;
}

.stat-donut-label {
    position: absolute; inset: 0;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    pointer-events: none;
}

/* Angka & label tengah: fade+slide lembut */
.stat-center-num,
.stat-center-label {
    opacity: 0;
    transform: translateY(6px);
    transition: opacity 0.8s cubic-bezier(0.16,1,0.3,1),
                transform 0.8s cubic-bezier(0.16,1,0.3,1);
}
.stat-center-num.txt-visible,
.stat-center-label.txt-visible {
    opacity: 1;
    transform: translateY(0);
}

/* ── Mini donut card ── */
.mini-donut-card {
    background: #f8fdf9;
    border: 1.5px solid rgba(22,163,74,0.12);
    border-radius: 1.25rem;
    padding: 1.25rem 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.75s cubic-bezier(0.16,1,0.3,1),
                transform 0.75s cubic-bezier(0.16,1,0.3,1),
                box-shadow 0.3s ease,
                border-color 0.3s ease;
}
.mini-donut-card.card-visible {
    opacity: 1;
    transform: translateY(0);
}
.mini-donut-card.card-visible:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 28px rgba(22,163,74,0.10);
    border-color: rgba(22,163,74,0.25);
}
.mini-donut-card::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 52px; height: 52px;
    background: rgba(22,163,74,0.055);
    border-bottom-left-radius: 100%;
    pointer-events: none;
}
.mini-donut-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 36px; height: 36px;
    background: rgba(22,163,74,0.04);
    border-top-right-radius: 100%;
    pointer-events: none;
}

/* Chip */
.mini-donut-chip {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #16a34a;
    background: rgba(22,163,74,0.09);
    border-radius: 99px;
    padding: 2px 10px;
    margin-bottom: 10px;
    display: inline-block;
    position: relative;
    z-index: 1;
}

/* Mini ring */
.mini-ring-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.mini-ring-pct {
    position: absolute;
    font-size: 11px;
    font-weight: 800;
    pointer-events: none;
    line-height: 1;
    opacity: 0;
    transition: opacity 0.7s cubic-bezier(0.16,1,0.3,1);
}
.mini-ring-pct.pct-visible {
    opacity: 1;
}

/* Stat number */
.stat-num-anim {
    opacity: 0;
    transform: translateY(6px);
    transition: opacity 0.7s cubic-bezier(0.16,1,0.3,1),
                transform 0.7s cubic-bezier(0.16,1,0.3,1);
}
.stat-num-anim.num-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<section id="statistik" class="relative pt-4 pb-24 md:pt-10 bg-white overflow-hidden">
    {{-- Background Grid --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="small-grid" width="24" height="24" patternUnits="userSpaceOnUse">
                    <path d="M 24 0 L 0 0 0 24" fill="none" stroke="rgba(22,163,74,0.08)" stroke-width="0.7"/>
                </pattern>
                <pattern id="large-grid" width="120" height="120" patternUnits="userSpaceOnUse">
                    <rect width="120" height="120" fill="url(#small-grid)"/>
                    <path d="M 120 0 L 0 0 0 120" fill="none" stroke="rgba(22,163,74,0.18)" stroke-width="1.2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#large-grid)"/>
            <rect x="0"   y="0"   width="120" height="120" fill="rgba(22,163,74,0.025)"/>
            <rect x="120" y="120" width="120" height="120" fill="rgba(22,163,74,0.025)"/>
            <rect x="240" y="0"   width="120" height="120" fill="rgba(22,163,74,0.018)"/>
            <rect x="0"   y="240" width="120" height="120" fill="rgba(22,163,74,0.018)"/>
            <rect x="100%" y="100%" width="120" height="120" transform="translate(-240,-240)" fill="rgba(22,163,74,0.025)"/>
            <rect x="100%" y="100%" width="120" height="120" transform="translate(-360,-120)" fill="rgba(22,163,74,0.018)"/>
            <rect x="100%" y="100%" width="120" height="120" transform="translate(-120,-360)" fill="rgba(22,163,74,0.018)"/>
            <line x1="60"  y1="0"    x2="60"  y2="100%" stroke="rgba(22,163,74,0.06)" stroke-width="1" stroke-dasharray="2 22"/>
            <line x1="180" y1="0"    x2="180" y2="100%" stroke="rgba(22,163,74,0.04)" stroke-width="1" stroke-dasharray="2 22"/>
            <line x1="0"   y1="60"  x2="100%" y2="60"   stroke="rgba(22,163,74,0.06)" stroke-width="1" stroke-dasharray="2 22"/>
            <line x1="0"   y1="180" x2="100%" y2="180"  stroke="rgba(22,163,74,0.04)" stroke-width="1" stroke-dasharray="2 22"/>
            <circle cx="0"   cy="0"   r="2.5" fill="rgba(22,163,74,0.15)"/>
            <circle cx="120" cy="0"   r="2.5" fill="rgba(22,163,74,0.12)"/>
            <circle cx="240" cy="0"   r="2.5" fill="rgba(22,163,74,0.12)"/>
            <circle cx="360" cy="0"   r="2.5" fill="rgba(22,163,74,0.10)"/>
            <circle cx="0"   cy="120" r="2.5" fill="rgba(22,163,74,0.12)"/>
            <circle cx="0"   cy="240" r="2.5" fill="rgba(22,163,74,0.10)"/>
            <circle cx="120" cy="120" r="3"   fill="rgba(22,163,74,0.18)"/>
            <circle cx="240" cy="240" r="3"   fill="rgba(22,163,74,0.15)"/>
            <circle cx="100%" cy="100%" r="2.5" transform="translate(-120,-120)" fill="rgba(22,163,74,0.15)"/>
            <circle cx="100%" cy="100%" r="2.5" transform="translate(-240,-120)" fill="rgba(22,163,74,0.12)"/>
            <circle cx="100%" cy="100%" r="2.5" transform="translate(-360,-120)" fill="rgba(22,163,74,0.10)"/>
            <circle cx="100%" cy="100%" r="2.5" transform="translate(-120,-240)" fill="rgba(22,163,74,0.12)"/>
            <circle cx="100%" cy="100%" r="2.5" transform="translate(-120,-360)" fill="rgba(22,163,74,0.10)"/>
            <circle cx="100%" cy="100%" r="3"   transform="translate(-240,-240)" fill="rgba(22,163,74,0.18)"/>
            <radialGradient id="centerFade" cx="50%" cy="50%" r="55%">
                <stop offset="0%"   stop-color="white" stop-opacity="0.92"/>
                <stop offset="70%"  stop-color="white" stop-opacity="0.60"/>
                <stop offset="100%" stop-color="white" stop-opacity="0.10"/>
            </radialGradient>
            <rect width="100%" height="100%" fill="url(#centerFade)"/>
        </svg>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">

        {{-- HEADER --}}
        <div class="text-center mb-14 stat-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                Dampak Nyata
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                Pencapaian
                <span class="relative inline-block text-primary-600">
                    Niat Zakat
                    <svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11" preserveAspectRatio="none">
                        <path class="stat-underline-path" id="statUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6"/>
                    </svg>
                </span>
            </h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Bersama-sama kita telah membuat perubahan positif nyata bagi sesama yang membutuhkan.
            </p>
        </div>

        {{-- LAYOUT: donut utama + 4 mini donuts --}}
        <div class="flex flex-col items-center gap-12">

            {{-- Main donut --}}
            <div class="stat-donut-outer" id="mainDonutOuter">
                <canvas id="statsDonutChart" width="320" height="320"></canvas>
                <div class="stat-donut-label">
                    <p class="text-5xl font-black text-green-600 leading-none stat-center-num" id="mainDonutNum">{{ $statLembaga }}</p>
                    <p class="text-xs text-neutral-400 font-medium mt-2 tracking-wide stat-center-label" id="mainDonutLabel">Lembaga Terdaftar</p>
                </div>
            </div>

            {{-- 4 mini donuts --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 md:gap-8 w-full max-w-3xl">

                {{-- Muzaki --}}
                <div class="mini-donut-card" data-card-index="0">
                    <span class="mini-donut-chip">Muzaki</span>
                    <div class="mini-ring-wrap w-24 h-24 mb-3">
                        <canvas id="ringMuzaki" width="96" height="96"></canvas>
                        <span class="mini-ring-pct" id="pctMuzaki" style="color:#16a34a;">{{ $pMuzaki }}%</span>
                    </div>
                    <p class="text-xl font-black text-neutral-800 leading-none stat-num-anim">{{ $statMuzaki }}</p>
                    <p class="text-xs text-neutral-400 mt-1.5 leading-snug">Muzaki Terdaftar</p>
                </div>

                {{-- Mustahik --}}
                <div class="mini-donut-card" data-card-index="1">
                    <span class="mini-donut-chip" style="color:#059669;background:rgba(5,150,105,0.09);">Mustahik</span>
                    <div class="mini-ring-wrap w-24 h-24 mb-3">
                        <canvas id="ringMustahik" width="96" height="96"></canvas>
                        <span class="mini-ring-pct" id="pctMustahik" style="color:#059669;">{{ $pMustahik }}%</span>
                    </div>
                    <p class="text-xl font-black text-neutral-800 leading-none stat-num-anim">{{ $statMustahik }}</p>
                    <p class="text-xs text-neutral-400 mt-1.5 leading-snug">Mustahik Terbantu</p>
                </div>

                {{-- Dana --}}
                <div class="mini-donut-card" data-card-index="2">
                    <span class="mini-donut-chip" style="color:#0d9488;background:rgba(13,148,136,0.09);">Dana</span>
                    <div class="mini-ring-wrap w-24 h-24 mb-3">
                        <canvas id="ringDana" width="96" height="96"></canvas>
                        <span class="mini-ring-pct" id="pctDana" style="color:#0d9488;">{{ $pDana }}%</span>
                    </div>
                    <p class="text-xl font-black text-neutral-800 leading-none stat-num-anim">Rp {{ $statDana }}</p>
                    <p class="text-xs text-neutral-400 mt-1.5 leading-snug">Dana Tersalurkan</p>
                </div>

                {{-- Program --}}
                <div class="mini-donut-card" data-card-index="3">
                    <span class="mini-donut-chip" style="color:#65a30d;background:rgba(101,163,13,0.09);">Program</span>
                    <div class="mini-ring-wrap w-24 h-24 mb-3">
                        <canvas id="ringProgram" width="96" height="96"></canvas>
                        <span class="mini-ring-pct" id="pctProgram" style="color:#65a30d;">{{ $pProgram }}%</span>
                    </div>
                    <p class="text-xl font-black text-neutral-800 leading-none stat-num-anim">{{ $statProgram }}</p>
                    <p class="text-xs text-neutral-400 mt-1.5 leading-snug">Program Aktif</p>
                </div>

            </div>
        </div>

    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    var chartsCreated = false;
    var pMuzaki   = {{ $pMuzaki }};
    var pMustahik = {{ $pMustahik }};
    var pDana     = {{ $pDana }};
    var pProgram  = {{ $pProgram }};

    function makeRing(id, pct, color, onDone) {
        var el = document.getElementById(id);
        if (!el) return;
        new Chart(el, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [pct, 100 - pct],
                    backgroundColor: [color, '#e9f7ef'],
                    borderWidth: 0,
                    hoverOffset: 0
                }]
            },
            options: {
                cutout: '74%',
                responsive: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                animation: {
                    animateRotate: true,
                    duration: 1600,
                    easing: 'easeInOutQuart',
                    onComplete: function() { if (onDone) onDone(); }
                },
                events: []
            }
        });
    }

    function buildCharts() {
        if (chartsCreated) return;
        chartsCreated = true;

        /* ── Main donut: lembut fade+slide masuk ── */
        var outer = document.getElementById('mainDonutOuter');
        setTimeout(function() {
            if (outer) outer.classList.add('donut-visible');
        }, 100);

        /* Main donut chart */
        var ctx = document.getElementById('statsDonutChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Muzaki', 'Mustahik', 'Dana', 'Program'],
                    datasets: [{
                        data: [pMuzaki, pMustahik, pDana, pProgram],
                        backgroundColor: ['#16a34a', '#059669', '#0d9488', '#65a30d'],
                        borderColor: ['#fff','#fff','#fff','#fff'],
                        borderWidth: 5,
                        hoverOffset: 8,
                        borderRadius: 4,
                    }]
                },
                options: {
                    cutout: '72%',
                    responsive: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(c) {
                                    return '  ' + [
                                        'Muzaki: {{ $statMuzaki }}',
                                        'Mustahik: {{ $statMustahik }}',
                                        'Dana: Rp {{ $statDana }}',
                                        'Program: {{ $statProgram }}'
                                    ][c.dataIndex];
                                }
                            },
                            backgroundColor: '#1a1a1a',
                            padding: 12,
                            cornerRadius: 10
                        }
                    },
                    animation: {
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeInOutQuart',
                        onComplete: function() {
                            /* Angka & label muncul setelah chart selesai */
                            setTimeout(function() {
                                var num = document.getElementById('mainDonutNum');
                                var lbl = document.getElementById('mainDonutLabel');
                                if (num) num.classList.add('txt-visible');
                                setTimeout(function() {
                                    if (lbl) lbl.classList.add('txt-visible');
                                    /* Breathing glow mulai setelah semua muncul */
                                    setTimeout(function() {
                                        if (outer) {
                                            outer.classList.remove('donut-visible');
                                            outer.classList.add('donut-breathing');
                                            /* Pastikan tetap terlihat */
                                            outer.style.opacity = '1';
                                            outer.style.transform = 'translateY(0) scale(1)';
                                        }
                                    }, 500);
                                }, 150);
                            }, 80);
                        }
                    }
                }
            });
        }

        /* ── Mini cards + rings: staggered lembut ── */
        var rings = [
            { id: 'ringMuzaki',   pct: pMuzaki,   color: '#16a34a', pctId: 'pctMuzaki'   },
            { id: 'ringMustahik', pct: pMustahik, color: '#059669', pctId: 'pctMustahik' },
            { id: 'ringDana',     pct: pDana,     color: '#0d9488', pctId: 'pctDana'     },
            { id: 'ringProgram',  pct: pProgram,  color: '#65a30d', pctId: 'pctProgram'  },
        ];

        var cards = document.querySelectorAll('.mini-donut-card');

        rings.forEach(function(r, i) {
            var baseDelay = i * 150 + 250;

            /* Card slide-in */
            setTimeout(function() {
                if (cards[i]) cards[i].classList.add('card-visible');
            }, baseDelay);

            /* Ring spin — mulai bersamaan dengan card */
            setTimeout(function() {
                makeRing(r.id, Math.max(r.pct, 2), r.color, function() {
                    /* Persen & angka muncul setelah ring selesai */
                    var pctEl = document.getElementById(r.pctId);
                    if (pctEl) pctEl.classList.add('pct-visible');

                    var numEl = cards[i] ? cards[i].querySelector('.stat-num-anim') : null;
                    if (numEl) {
                        setTimeout(function() {
                            numEl.classList.add('num-visible');
                        }, 120);
                    }
                });
            }, baseDelay + 80);
        });
    }

    var section = document.getElementById('statistik');
    if (section && 'IntersectionObserver' in window) {

        var chartObs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) { buildCharts(); chartObs.disconnect(); }
            });
        }, { threshold: 0.15 });
        chartObs.observe(section);

        var revealObs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) e.target.classList.add('is-visible');
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('#statistik .stat-reveal').forEach(function(el) {
            revealObs.observe(el);
        });

        var ulPath = document.getElementById('statUnderlinePath');
        if (ulPath) {
            var ulObs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) {
                    if (e.isIntersecting) { ulPath.classList.add('draw'); ulObs.unobserve(e.target); }
                });
            }, { threshold: 0.4 });
            ulObs.observe(section);
        }

    } else {
        buildCharts();
        document.querySelectorAll('#statistik .stat-reveal').forEach(function(el) {
            el.classList.add('is-visible');
        });
    }
})();
</script>