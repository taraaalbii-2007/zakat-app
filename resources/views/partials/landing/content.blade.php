{{-- resources/views/partials/landing/content.blade.php --}}

{{-- ============================================================
     GLOBAL SCROLL ANIMATION STYLES
     ============================================================ --}}
<style>
.nz-reveal {
    opacity: 0;
    transform: translateY(32px);
    transition: opacity 0.7s cubic-bezier(0.4,0,0.2,1), transform 0.7s cubic-bezier(0.4,0,0.2,1);
}
.nz-reveal.nz-visible { opacity: 1; transform: translateY(0); }

.nz-reveal-left {
    opacity: 0;
    transform: translateX(-40px);
    transition: opacity 0.7s cubic-bezier(0.4,0,0.2,1), transform 0.7s cubic-bezier(0.4,0,0.2,1);
}
.nz-reveal-left.nz-visible { opacity: 1; transform: translateX(0); }

.nz-reveal-right {
    opacity: 0;
    transform: translateX(40px);
    transition: opacity 0.7s cubic-bezier(0.4,0,0.2,1), transform 0.7s cubic-bezier(0.4,0,0.2,1);
}
.nz-reveal-right.nz-visible { opacity: 1; transform: translateX(0); }

.nz-reveal-scale {
    opacity: 0;
    transform: scale(0.88);
    transition: opacity 0.8s cubic-bezier(0.4,0,0.2,1), transform 0.8s cubic-bezier(0.4,0,0.2,1);
}
.nz-reveal-scale.nz-visible { opacity: 1; transform: scale(1); }
</style>


{{-- ============================================================
     SECTION 1 — FITUR
     Background character: Hexagon wireframe grid
     ============================================================ --}}
<section id="fitur" class="relative py-20 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="hex-pat" x="0" y="0" width="60" height="52" patternUnits="userSpaceOnUse">
                    <polygon points="30,2 58,17 58,47 30,62 2,47 2,17" fill="none" stroke="rgba(45,105,54,0.07)" stroke-width="1.2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hex-pat)"/>
        </svg>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 75% 65% at 50% 50%, rgba(255,255,255,0) 0%, rgba(255,255,255,0.6) 100%);"></div>
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full" style="background:radial-gradient(circle, rgba(45,105,54,0.06) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full" style="background:radial-gradient(circle, rgba(45,105,54,0.05) 0%, transparent 70%);"></div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">
        <div class="text-center mb-16 nz-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Fitur Unggulan</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Kenapa Memilih <span class="text-primary-600">Niat Zakat?</span></h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">Platform zakat digital yang transparan, aman, dan mudah digunakan untuk berbagai kebutuhan ibadah Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <div class="group nz-reveal" style="transition-delay:0.05s">
                <div class="relative h-full bg-white rounded-2xl p-8 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-neutral-200 hover:border-primary-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>
                    <div class="absolute top-4 right-4 text-6xl font-black text-primary-50 select-none pointer-events-none leading-none">01</div>
                    <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-100 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Transparan & Terpercaya</h3>
                    <p class="text-neutral-600 leading-relaxed">Setiap transaksi tercatat dengan detail dan dapat dilacak secara real-time. Laporan keuangan tersedia untuk semua donatur.</p>
                </div>
            </div>

            <div class="group nz-reveal" style="transition-delay:0.12s">
                <div class="relative h-full bg-white rounded-2xl p-8 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-neutral-200 hover:border-primary-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>
                    <div class="absolute top-4 right-4 text-6xl font-black text-primary-50 select-none pointer-events-none leading-none">02</div>
                    <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-100 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Kalkulator Zakat Otomatis</h3>
                    <p class="text-neutral-600 leading-relaxed">Hitung zakat mal, profesi, dan fitrah dengan mudah. Sistem kami menghitung secara otomatis berdasarkan nisab terkini.</p>
                </div>
            </div>

            <div class="group nz-reveal" style="transition-delay:0.19s">
                <div class="relative h-full bg-white rounded-2xl p-8 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-neutral-200 hover:border-primary-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>
                    <div class="absolute top-4 right-4 text-6xl font-black text-primary-50 select-none pointer-events-none leading-none">03</div>
                    <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-100 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Pembayaran Fleksibel</h3>
                    <p class="text-neutral-600 leading-relaxed">Berbagai metode pembayaran tersedia: transfer bank, e-wallet, QRIS, dan virtual account untuk kemudahan Anda.</p>
                </div>
            </div>

            <div class="group nz-reveal" style="transition-delay:0.26s">
                <div class="relative h-full bg-white rounded-2xl p-8 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-neutral-200 hover:border-primary-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>
                    <div class="absolute top-4 right-4 text-6xl font-black text-primary-50 select-none pointer-events-none leading-none">04</div>
                    <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-100 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Laporan Real-Time</h3>
                    <p class="text-neutral-600 leading-relaxed">Pantau penyaluran zakat Anda secara langsung. Dapatkan notifikasi dan laporan lengkap distribusi dana.</p>
                </div>
            </div>

            <div class="group nz-reveal" style="transition-delay:0.33s">
                <div class="relative h-full bg-white rounded-2xl p-8 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-neutral-200 hover:border-primary-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>
                    <div class="absolute top-4 right-4 text-6xl font-black text-primary-50 select-none pointer-events-none leading-none">05</div>
                    <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-100 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Penyaluran Terverifikasi</h3>
                    <p class="text-neutral-600 leading-relaxed">Mustahik terverifikasi dan tersalurkan tepat sasaran. Kami memastikan zakat sampai ke yang berhak.</p>
                </div>
            </div>

            <div class="group nz-reveal" style="transition-delay:0.40s">
                <div class="relative h-full bg-white rounded-2xl p-8 shadow-soft hover:shadow-card-hover transition-all duration-300 border border-neutral-200 hover:border-primary-300 overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-primary-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rounded-t-2xl"></div>
                    <div class="absolute top-4 right-4 text-6xl font-black text-primary-50 select-none pointer-events-none leading-none">06</div>
                    <div class="w-14 h-14 bg-primary-50 rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary-100 transition-colors duration-300">
                        <svg class="w-7 h-7 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Bukti Digital</h3>
                    <p class="text-neutral-600 leading-relaxed">Dapatkan bukti pembayaran dan sertifikat zakat digital yang sah untuk keperluan administrasi pajak.</p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ============================================================
     SECTION 2 — STATISTIK
     Background character: Dot grid + concentric decorative rings
     Charts: scroll-triggered via IntersectionObserver
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
    $statMuzaki   = $fmtStat((int) ($totalMuzaki ?? 0));
    $statMustahik = $fmtStat((int) ($totalMustahik ?? 0));
    $statDana     = $fmtDana((float) ($totalDana ?? 0));
    $statProgram  = $fmtStat((int) ($totalProgram ?? 0));
    $rawMuzaki   = max((int)($totalMuzaki ?? 1), 1);
    $rawMustahik = max((int)($totalMustahik ?? 1), 1);
    $rawProgram  = max((int)($totalProgram ?? 1), 1);
    $rawDana     = max((float)($totalDana ?? 1), 1);
    $total4      = $rawMuzaki + $rawMustahik + $rawProgram + ($rawDana / 1_000_000);
    $pMuzaki     = round($rawMuzaki / $total4 * 100, 1);
    $pMustahik   = round($rawMustahik / $total4 * 100, 1);
    $pProgram    = round($rawProgram / $total4 * 100, 1);
    $pDana       = round(100 - $pMuzaki - $pMustahik - $pProgram, 1);
@endphp

<section id="statistik" class="relative py-20 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        {{-- Dot grid --}}
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="dot-pat" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.3" fill="rgba(45,105,54,0.09)"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#dot-pat)"/>
        </svg>
        {{-- Concentric decorative rings centered --}}
        <div class="absolute" style="left:50%;top:50%;transform:translate(-50%,-50%);width:680px;height:680px;border-radius:50%;border:1.5px solid rgba(45,105,54,0.06);"></div>
        <div class="absolute" style="left:50%;top:50%;transform:translate(-50%,-50%);width:490px;height:490px;border-radius:50%;border:1.5px solid rgba(45,105,54,0.09);"></div>
        <div class="absolute" style="left:50%;top:50%;transform:translate(-50%,-50%);width:310px;height:310px;border-radius:50%;border:1.5px solid rgba(45,105,54,0.12);"></div>
        {{-- White fade over rings so content stays crisp --}}
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 60% 55% at 50% 50%, rgba(255,255,255,0.82) 0%, transparent 100%);"></div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">
        <div class="text-center mb-16 nz-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Dampak Nyata</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Pencapaian <span class="text-primary-600">Niat Zakat</span></h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">Bersama-sama kita telah membuat perubahan positif bagi sesama</p>
        </div>

        <style>
        .stat-ring-wrap { position:relative; display:inline-flex; align-items:center; justify-content:center; }
        .stat-ring-label { position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; pointer-events:none; }
        </style>

        <div class="flex flex-col items-center gap-14">
            <div class="stat-ring-wrap w-72 h-72 nz-reveal-scale">
                <canvas id="statsDonutChart" width="288" height="288"></canvas>
                <div class="stat-ring-label">
                    <p class="text-4xl font-black text-primary-600 leading-none">{{ $statMuzaki }}</p>
                    <p class="text-sm text-neutral-500 font-medium mt-1">Total Muzaki</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 md:gap-16">
                <div class="flex flex-col items-center gap-3 nz-reveal" style="transition-delay:0.05s">
                    <div class="stat-ring-wrap w-32 h-32">
                        <canvas id="ringMuzaki" width="128" height="128"></canvas>
                        <div class="stat-ring-label"><p class="text-lg font-black text-primary-600 leading-none">{{ $statMuzaki }}</p></div>
                    </div>
                    <div class="text-center"><p class="text-sm font-bold text-neutral-800">Muzaki</p><p class="text-xs text-neutral-500">Terdaftar</p></div>
                </div>
                <div class="flex flex-col items-center gap-3 nz-reveal" style="transition-delay:0.15s">
                    <div class="stat-ring-wrap w-32 h-32">
                        <canvas id="ringMustahik" width="128" height="128"></canvas>
                        <div class="stat-ring-label"><p class="text-lg font-black text-primary-600 leading-none">{{ $statMustahik }}</p></div>
                    </div>
                    <div class="text-center"><p class="text-sm font-bold text-neutral-800">Mustahik</p><p class="text-xs text-neutral-500">Terbantu</p></div>
                </div>
                <div class="flex flex-col items-center gap-3 nz-reveal" style="transition-delay:0.25s">
                    <div class="stat-ring-wrap w-32 h-32">
                        <canvas id="ringDana" width="128" height="128"></canvas>
                        <div class="stat-ring-label"><p class="text-lg font-black text-primary-600 leading-none">{{ $statDana }}</p></div>
                    </div>
                    <div class="text-center"><p class="text-sm font-bold text-neutral-800">Dana IDR</p><p class="text-xs text-neutral-500">Tersalurkan</p></div>
                </div>
                <div class="flex flex-col items-center gap-3 nz-reveal" style="transition-delay:0.35s">
                    <div class="stat-ring-wrap w-32 h-32">
                        <canvas id="ringProgram" width="128" height="128"></canvas>
                        <div class="stat-ring-label"><p class="text-lg font-black text-primary-600 leading-none">{{ $statProgram }}</p></div>
                    </div>
                    <div class="text-center"><p class="text-sm font-bold text-neutral-800">Program</p><p class="text-xs text-neutral-500">Tersalurkan</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    var chartsCreated = false;
    var donutData  = [{{ $pMuzaki }}, {{ $pMustahik }}, {{ $pDana }}, {{ $pProgram }}];
    var statLabels = ['Muzaki: {{ $statMuzaki }}','Mustahik: {{ $statMustahik }}','Dana: Rp {{ $statDana }}','Program: {{ $statProgram }}'];

    function buildCharts() {
        if (chartsCreated) return;
        chartsCreated = true;

        var ctx = document.getElementById('statsDonutChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Muzaki','Mustahik','Dana','Program'],
                    datasets: [{ data: donutData, backgroundColor: ['#2d6936','#7cb342','#aed581','#dcedc8'], borderColor: ['#fff','#fff','#fff','#fff'], borderWidth: 5, hoverOffset: 10 }]
                },
                options: {
                    cutout: '72%', responsive: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: function(c){ return ' '+statLabels[c.dataIndex]; } }, backgroundColor: '#2d6936', padding: 10, cornerRadius: 8 }
                    },
                    animation: { animateRotate: true, duration: 1400, easing: 'easeInOutQuart' }
                }
            });
        }

        function makeRing(id, pct, colors) {
            var el = document.getElementById(id); if (!el) return;
            new Chart(el, {
                type: 'doughnut',
                data: { datasets: [{ data: [pct, 100-pct], backgroundColor: colors, borderWidth: 0, hoverOffset: 0 }] },
                options: { cutout: '78%', responsive: false, plugins: { legend:{display:false}, tooltip:{enabled:false} }, animation: { animateRotate:true, duration:1200, easing:'easeInOutQuart' }, events: [] }
            });
        }
        makeRing('ringMuzaki',   Math.min(Math.round({{ $pMuzaki }}),   99), ['#2d6936','#dcedc8']);
        makeRing('ringMustahik', Math.min(Math.round({{ $pMustahik }}), 99), ['#7cb342','#dcedc8']);
        makeRing('ringDana',     Math.min(Math.round({{ $pDana }}),     99), ['#aed581','#e8f5e9']);
        makeRing('ringProgram',  Math.min(Math.round({{ $pProgram }}),  99), ['#2d6936','#dcedc8']);
    }

    // Fire charts only when section scrolls into view
    var section = document.getElementById('statistik');
    if (section && 'IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) { if (e.isIntersecting) { buildCharts(); obs.disconnect(); } });
        }, { threshold: 0.18 });
        obs.observe(section);
    } else { buildCharts(); }
})();
</script>


{{-- ============================================================
     SECTION 3 — CARA KERJA
     Background character: Diagonal dashed lines + animated floating boxes
     ============================================================ --}}
<style>
@keyframes numberPulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(45,105,54,0.35); }
    50%       { box-shadow: 0 0 0 12px rgba(45,105,54,0); }
}
@keyframes nzFloatA {
    0%,100% { transform: rotate(12deg) translateY(0px); }
    50%      { transform: rotate(12deg) translateY(-9px); }
}
@keyframes nzFloatB {
    0%,100% { transform: rotate(-8deg) translateY(0px); }
    50%      { transform: rotate(-8deg) translateY(-7px); }
}
@keyframes nzFloatC {
    0%,100% { transform: rotate(5deg) translateY(0px); }
    50%      { transform: rotate(5deg) translateY(-11px); }
}
@keyframes nzFloatD {
    0%,100% { transform: rotate(-14deg) translateY(0px); }
    50%      { transform: rotate(-14deg) translateY(-6px); }
}
.nz-fb-a { animation: nzFloatA 5s ease-in-out infinite; }
.nz-fb-b { animation: nzFloatB 6s ease-in-out 0.8s infinite; }
.nz-fb-c { animation: nzFloatC 7s ease-in-out 1.6s infinite; }
.nz-fb-d { animation: nzFloatD 5.5s ease-in-out 0.4s infinite; }
.how-num-badge { animation: numberPulse 2.8s ease-in-out infinite; }
.how-dot    { width:12px; height:12px; border-radius:50%; background:#aed581; display:inline-block; }
.how-dot-sm { width:8px;  height:8px;  border-radius:50%; background:#dcedc8; display:inline-block; }
</style>

<section id="cara-kerja" class="relative py-20 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        {{-- Diagonal dashed lines pattern --}}
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="diag-pat" x="0" y="0" width="56" height="56" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="56" x2="56" y2="0" stroke="rgba(45,105,54,0.055)" stroke-width="1" stroke-dasharray="4 7"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#diag-pat)"/>
        </svg>
        {{-- Floating boxes --}}
        <div class="nz-fb-a absolute rounded-2xl border-2" style="width:88px;height:88px;top:7%;left:4%;border-color:rgba(45,105,54,0.12);background:rgba(45,105,54,0.025);transform:rotate(12deg);"></div>
        <div class="nz-fb-b absolute rounded-xl border-2" style="width:60px;height:60px;top:15%;right:6%;border-color:rgba(45,105,54,0.09);background:rgba(45,105,54,0.02);transform:rotate(-8deg);"></div>
        <div class="nz-fb-c absolute rounded-2xl border"  style="width:110px;height:110px;bottom:10%;left:2%;border-color:rgba(45,105,54,0.08);background:rgba(45,105,54,0.015);transform:rotate(5deg);"></div>
        <div class="nz-fb-d absolute rounded-xl border"   style="width:68px;height:68px;bottom:18%;right:4%;border-color:rgba(45,105,54,0.1);background:rgba(45,105,54,0.02);transform:rotate(-14deg);"></div>
        {{-- Center clarity wash --}}
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 65% 70% at 50% 50%, rgba(255,255,255,0.78) 0%, transparent 100%);"></div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">
        <div class="text-center mb-16 nz-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Mudah & Cepat</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Cara Kerja <span class="text-primary-600">Niat Zakat</span></h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">Mulai kelola zakat lembaga Anda hanya dalam 3 langkah</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-12 md:space-y-0">
            {{-- Step 1 --}}
            <div class="flex flex-col md:flex-row items-center gap-6 md:gap-12">
                <div class="flex-shrink-0 flex flex-col items-center md:items-end w-full md:w-1/2 md:pr-6 nz-reveal-left">
                    <div class="flex items-center gap-4 md:flex-row-reverse">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz">
                            <span class="text-2xl font-black text-white">1</span>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end"><span class="how-dot"></span><span class="how-dot-sm"></span></div>
                    </div>
                </div>
                <div class="flex-1 bg-primary-50 rounded-2xl p-7 border border-primary-100 md:w-1/2 nz-reveal-right">
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Daftar &amp; Verifikasi</h3>
                    <p class="text-neutral-600 leading-relaxed">Daftarkan lembaga atau masjid Anda. Tim kami akan memverifikasi dan mengaktifkan akun dalam 1&times;24 jam.</p>
                </div>
            </div>
            <div class="hidden md:flex justify-center"><div class="w-px h-12 bg-gradient-to-b from-primary-300 to-primary-100 opacity-60"></div></div>

            {{-- Step 2 --}}
            <div class="flex flex-col md:flex-row-reverse items-center gap-6 md:gap-12">
                <div class="flex-shrink-0 flex flex-col items-center md:items-start w-full md:w-1/2 md:pl-6 nz-reveal-right">
                    <div class="flex items-center gap-4">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz" style="animation-delay:0.7s">
                            <span class="text-2xl font-black text-white">2</span>
                        </div>
                        <div class="flex flex-col gap-2"><span class="how-dot"></span><span class="how-dot-sm"></span></div>
                    </div>
                </div>
                <div class="flex-1 bg-primary-50 rounded-2xl p-7 border border-primary-100 md:w-1/2 nz-reveal-left">
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Setup &amp; Konfigurasi</h3>
                    <p class="text-neutral-600 leading-relaxed">Atur jenis zakat, kategori mustahik, data amil, dan konfigurasi masjid sesuai kebutuhan lembaga Anda.</p>
                </div>
            </div>
            <div class="hidden md:flex justify-center"><div class="w-px h-12 bg-gradient-to-b from-primary-300 to-primary-100 opacity-60"></div></div>

            {{-- Step 3 --}}
            <div class="flex flex-col md:flex-row items-center gap-6 md:gap-12">
                <div class="flex-shrink-0 flex flex-col items-center md:items-end w-full md:w-1/2 md:pr-6 nz-reveal-left">
                    <div class="flex items-center gap-4 md:flex-row-reverse">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz" style="animation-delay:1.4s">
                            <span class="text-2xl font-black text-white">3</span>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end"><span class="how-dot"></span><span class="how-dot-sm"></span></div>
                    </div>
                </div>
                <div class="flex-1 bg-primary-50 rounded-2xl p-7 border border-primary-100 md:w-1/2 nz-reveal-right">
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Kelola &amp; Laporkan</h3>
                    <p class="text-neutral-600 leading-relaxed">Mulai catat penerimaan, salurkan ke mustahik, dan <em>generate</em> laporan konsolidasi kapan saja — transparan dan akuntabel.</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     SECTION 4 — TESTIMONI
     Background character: SVG wave shapes (top & bottom) + horizontal line grid
     ============================================================ --}}
<section id="testimoni" class="relative py-20 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        {{-- Horizontal line grid --}}
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="hline-pat" x="0" y="0" width="100%" height="36" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="35.5" x2="100%" y2="35.5" stroke="rgba(45,105,54,0.045)" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hline-pat)"/>
        </svg>
        {{-- Wave bottom --}}
        <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 180" preserveAspectRatio="none" style="height:180px;opacity:0.055;">
            <path fill="#2d6936" d="M0,96L60,90.7C120,85,240,75,360,80C480,85,600,107,720,106.7C840,107,960,85,1080,69.3C1200,53,1320,43,1380,37.3L1440,32L1440,180L0,180Z"/>
        </svg>
        {{-- Wave top (flipped) --}}
        <svg class="absolute top-0 right-0 w-full" viewBox="0 0 1440 140" preserveAspectRatio="none" style="height:140px;opacity:0.04;transform:scaleX(-1) scaleY(-1);">
            <path fill="#2d6936" d="M0,96L60,90.7C120,85,240,75,360,80C480,85,600,107,720,106.7C840,107,960,85,1080,69.3C1200,53,1320,43,1380,37.3L1440,32L1440,140L0,140Z"/>
        </svg>
        {{-- Center clarity --}}
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 80% 70% at 50% 45%, rgba(255,255,255,0.85) 0%, transparent 100%);"></div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">
        <div class="text-center mb-16 nz-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Testimoni</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">Apa Kata <span class="text-primary-600">Mereka?</span></h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">Cerita inspiratif dari para muzaki yang telah mempercayakan zakatnya melalui platform kami</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="nz-reveal" style="transition-delay:0.05s">
                <div class="relative bg-white rounded-2xl p-8 shadow-card hover:shadow-card-hover transition-all duration-300 h-full border border-neutral-100">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center opacity-50">
                        <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-center space-x-1 mb-4">
                            @for($i = 0; $i < 5; $i++)<svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>@endfor
                        </div>
                        <p class="text-neutral-700 leading-relaxed italic">"Platform yang sangat memudahkan! Saya bisa tracking zakat saya kemana disalurkan. Transparan dan terpercaya."</p>
                    </div>
                    <div class="flex items-center space-x-4 pt-4 border-t border-neutral-100">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center"><span class="text-primary-600 font-bold text-lg">AH</span></div>
                        <div><h4 class="font-semibold text-neutral-900">Ahmad Hidayat</h4><p class="text-sm text-neutral-500">Pengusaha</p></div>
                    </div>
                </div>
            </div>

            <div class="nz-reveal" style="transition-delay:0.15s">
                <div class="relative bg-white rounded-2xl p-8 shadow-card hover:shadow-card-hover transition-all duration-300 h-full border border-neutral-100">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center opacity-50">
                        <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-center space-x-1 mb-4">
                            @for($i = 0; $i < 5; $i++)<svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>@endfor
                        </div>
                        <p class="text-neutral-700 leading-relaxed italic">"Kalkulator zakatnya sangat membantu. Tidak perlu bingung lagi menghitung nisab dan kadar zakat. Recommended!"</p>
                    </div>
                    <div class="flex items-center space-x-4 pt-4 border-t border-neutral-100">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center"><span class="text-primary-600 font-bold text-lg">SF</span></div>
                        <div><h4 class="font-semibold text-neutral-900">Siti Fatimah</h4><p class="text-sm text-neutral-500">Profesional</p></div>
                    </div>
                </div>
            </div>

            <div class="nz-reveal" style="transition-delay:0.25s">
                <div class="relative bg-white rounded-2xl p-8 shadow-card hover:shadow-card-hover transition-all duration-300 h-full border border-neutral-100">
                    <div class="absolute top-6 right-6 w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center opacity-50">
                        <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>
                    <div class="mb-6">
                        <div class="flex items-center space-x-1 mb-4">
                            @for($i = 0; $i < 5; $i++)<svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>@endfor
                        </div>
                        <p class="text-neutral-700 leading-relaxed italic">"Laporan penyalurannya detail banget. Saya jadi tau persis kemana zakat saya. Alhamdulillah merasa lebih tenang."</p>
                    </div>
                    <div class="flex items-center space-x-4 pt-4 border-t border-neutral-100">
                        <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center"><span class="text-primary-600 font-bold text-lg">MR</span></div>
                        <div><h4 class="font-semibold text-neutral-900">Muhammad Rizki</h4><p class="text-sm text-neutral-500">Karyawan Swasta</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================
     GLOBAL SCROLL OBSERVER
     ============================================================ --}}
<script>
(function () {
    if (!('IntersectionObserver' in window)) {
        document.querySelectorAll('.nz-reveal,.nz-reveal-left,.nz-reveal-right,.nz-reveal-scale')
            .forEach(function(el){ el.classList.add('nz-visible'); });
        return;
    }
    var obs = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('nz-visible');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.nz-reveal,.nz-reveal-left,.nz-reveal-right,.nz-reveal-scale')
        .forEach(function(el){ obs.observe(el); });
})();
</script>