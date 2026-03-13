{{-- ============================================================
     SECTION: TESTIMONI
     resources/views/partials/landing/sections/testimoni.blade.php
     Carousel card modern dengan panah kiri/kanan + dots.
     ============================================================ --}}

<style>
/* ── Underline draw (sama pola cara-kerja) ── */
.tm-underline-path {
    fill: none;
    stroke: #16a34a;
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-dasharray: 240;
    stroke-dashoffset: 240;
}
.tm-underline-path.tm-draw {
    animation: tmDrawLine 1.1s cubic-bezier(0.4,0,0.2,1) 0.3s forwards;
}
@keyframes tmDrawLine {
    from { stroke-dashoffset: 240; }
    to   { stroke-dashoffset: 0; }
}

/* ── Background floaters ── */
@keyframes tmFloatA { 0%,100%{transform:rotate(12deg) translateY(0px)} 50%{transform:rotate(12deg) translateY(-9px)} }
@keyframes tmFloatB { 0%,100%{transform:rotate(-8deg) translateY(0px)} 50%{transform:rotate(-8deg) translateY(-7px)} }
@keyframes tmFloatC { 0%,100%{transform:rotate(5deg) translateY(0px)}  50%{transform:rotate(5deg) translateY(-11px)} }
@keyframes tmFloatD { 0%,100%{transform:rotate(-14deg) translateY(0px)} 50%{transform:rotate(-14deg) translateY(-6px)} }
.tm-fb-a { animation: tmFloatA 5s ease-in-out infinite; }
.tm-fb-b { animation: tmFloatB 6s ease-in-out 0.8s infinite; }
.tm-fb-c { animation: tmFloatC 7s ease-in-out 1.6s infinite; }
.tm-fb-d { animation: tmFloatD 5.5s ease-in-out 0.4s infinite; }

/* ── Card ── */
.tm-card {
    flex-shrink: 0;
    background: #fff;
    border-radius: 1.5rem;
    padding: 2rem 2rem 1.75rem;
    box-shadow: 0 4px 24px rgba(45,105,54,0.08), 0 1px 4px rgba(0,0,0,0.04);
    border: 1.5px solid rgba(45,105,54,0.08);
    position: relative;
    overflow: hidden;
    transition: box-shadow 0.3s, transform 0.3s;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.tm-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);
    border-radius: 1.5rem 1.5rem 0 0;
}
.tm-card.tm-active {
    box-shadow: 0 12px 40px rgba(45,105,54,0.16), 0 2px 8px rgba(0,0,0,0.06);
    transform: translateY(-4px) scale(1.015);
    border-color: rgba(45,105,54,0.2);
}

/* Quote icon */
.tm-quote-icon {
    position: absolute;
    top: 1.25rem; right: 1.5rem;
    color: rgba(45,105,54,0.12);
    font-size: 5rem;
    line-height: 1;
    font-family: Georgia, serif;
    font-weight: 900;
    pointer-events: none;
    user-select: none;
}

/* Stars */
.tm-stars { display: flex; gap: 3px; }
.tm-star-fill { color: #f59e0b; }
.tm-star-empty { color: #d1d5db; }

/* Quote text */
.tm-quote-text {
    font-size: 0.975rem;
    line-height: 1.7;
    color: #374151;
    font-style: italic;
    flex: 1;
}

/* Divider */
.tm-divider {
    height: 1px;
    background: linear-gradient(90deg, rgba(45,105,54,0.15), transparent);
    border-radius: 1px;
}

/* Avatar row */
.tm-avatar-row { display: flex; align-items: center; gap: 0.75rem; }
.tm-avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1rem; color: #fff;
    flex-shrink: 0;
    background: linear-gradient(135deg, #22c55e, #15803d);
    box-shadow: 0 2px 8px rgba(45,105,54,0.3);
}
.tm-avatar-name { font-weight: 700; font-size: 0.9rem; color: #111827; }
.tm-avatar-role { font-size: 0.78rem; color: #6b7280; margin-top: 1px; }

/* Badge lembaga */
.tm-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(45,105,54,0.07);
    color: #15803d;
    font-size: 0.7rem; font-weight: 600;
    padding: 3px 10px; border-radius: 999px;
    margin-left: auto;
}

/* ── Reveal (ikuti global observer) ── */
.nz-reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.nz-reveal.nz-visible { opacity: 1; transform: translateY(0); }
</style>

<section id="testimoni" class="relative pt-4 pb-24 md:pt-10 bg-white overflow-hidden">
    {{-- Background dekoratif --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="tm-dot-pat" x="0" y="0" width="28" height="28" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="rgba(45,105,54,0.045)"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#tm-dot-pat)"/>
        </svg>
        <div class="tm-fb-a absolute rounded-2xl border-2" style="width:80px;height:80px;top:6%;left:3%;border-color:rgba(45,105,54,0.1);background:rgba(45,105,54,0.02);transform:rotate(12deg);"></div>
        <div class="tm-fb-b absolute rounded-xl border-2"  style="width:56px;height:56px;top:14%;right:5%;border-color:rgba(45,105,54,0.08);background:rgba(45,105,54,0.015);transform:rotate(-8deg);"></div>
        <div class="tm-fb-c absolute rounded-2xl border"   style="width:100px;height:100px;bottom:8%;left:2%;border-color:rgba(45,105,54,0.07);background:rgba(45,105,54,0.01);transform:rotate(5deg);"></div>
        <div class="tm-fb-d absolute rounded-xl border"    style="width:64px;height:64px;bottom:16%;right:3%;border-color:rgba(45,105,54,0.09);background:rgba(45,105,54,0.015);transform:rotate(-14deg);"></div>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 65% 70% at 50% 50%, rgba(255,255,255,0.82) 0%, transparent 100%);"></div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">

        {{-- Heading --}}
        <div class="text-center mb-14 nz-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Testimoni</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                Apa Kata <span class="relative inline-block text-primary-600">Mereka (Muzakki)?<svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11" preserveAspectRatio="none"><path class="tm-underline-path" id="tmUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6" /></svg></span>
            </h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Cerita inspiratif dari para muzaki yang telah mempercayakan zakatnya melalui platform kami
            </p>
        </div>

        

        {{-- Grid 3 card --}}
        <div class="nz-reveal" style="transition-delay:0.15s">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="tmGrid">

                    @php
                    use Illuminate\Support\Str;

                    $testimonials = isset($testimonis) && $testimonis->isNotEmpty()
                        ? $testimonis->take(3)
                        : \App\Models\Testimoni::where('is_approved', true)->latest('approved_at')->take(3)->get();
                    @endphp

                    @forelse($testimonials as $i => $t)
                        @include('partials.landing.partials.testimoni-card', ['t' => $t, 'i' => $i])
                    @empty
                    <div class="md:col-span-3">
                        <div class="tm-card" style="align-items:center;justify-content:center;min-height:180px;">
                            <p class="text-neutral-400 text-sm text-center">Belum ada testimoni yang tersedia.</p>
                        </div>
                    </div>
                    @endforelse

            </div>
        </div>

    </div>
</section>

<script>
(function() {
    const tmSection = document.getElementById('testimoni');
    const tmPath    = document.getElementById('tmUnderlinePath');
    if (tmSection && tmPath && 'IntersectionObserver' in window) {
        new IntersectionObserver(([e]) => {
            if (e.isIntersecting) { tmPath.classList.add('tm-draw'); }
        }, { threshold: 0.4 }).observe(tmSection);
    } else if (tmPath) { tmPath.classList.add('tm-draw'); }
})();
</script>