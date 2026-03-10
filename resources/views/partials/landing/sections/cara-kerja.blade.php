{{-- ============================================================
     SECTION: CARA KERJA
     resources/views/partials/landing/sections/cara-kerja.blade.php
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

/* Character blink */
@keyframes ckBlink {
    0%,90%,100% { transform: scaleY(1); }
    95%          { transform: scaleY(0.1); }
}
/* Character wave hand */
@keyframes ckWave {
    0%,100% { transform: rotate(0deg); }
    25%      { transform: rotate(22deg); }
    75%      { transform: rotate(-12deg); }
}
/* Character bounce */
@keyframes ckBounce {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-5px); }
}
/* Checkmark/star pop */
@keyframes ckPop {
    0%   { transform: scale(0) rotate(-20deg); opacity:0; }
    60%  { transform: scale(1.2) rotate(5deg);  opacity:1; }
    100% { transform: scale(1) rotate(0deg);    opacity:1; }
}
/* Gear slow spin */
@keyframes ckSpin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}

/* ── Underline animasi draw ── */
.ck-underline-path {
    fill: none;
    stroke: #16a34a;
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-dasharray: 260;
    stroke-dashoffset: 260;
}
.ck-underline-path.ck-draw {
    animation: ckDrawLine 1.1s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
}
@keyframes ckDrawLine {
    from { stroke-dashoffset: 260; }
    to   { stroke-dashoffset: 0; }
}

.nz-fb-a { animation: nzFloatA 5s ease-in-out infinite; }
.nz-fb-b { animation: nzFloatB 6s ease-in-out 0.8s infinite; }
.nz-fb-c { animation: nzFloatC 7s ease-in-out 1.6s infinite; }
.nz-fb-d { animation: nzFloatD 5.5s ease-in-out 0.4s infinite; }
.how-num-badge { animation: numberPulse 2.8s ease-in-out infinite; }
.how-dot    { width:12px; height:12px; border-radius:50%; background:#aed581; display:inline-block; }
.how-dot-sm { width:8px;  height:8px;  border-radius:50%; background:#dcedc8; display:inline-block; }

/* Card character */
.step-card { position: relative; overflow: hidden; }
.ck-char-wrap {
    position: absolute;
    bottom: 0;
    right: 12px;
    width: 76px;
    height: 92px;
    pointer-events: none;
    animation: ckBounce 3s ease-in-out infinite;
}
/* Subtle shadow under character */
.ck-char-wrap::after {
    content: '';
    position: absolute;
    bottom: 1px;
    left: 50%;
    transform: translateX(-50%);
    width: 48px;
    height: 8px;
    background: rgba(22,163,74,0.15);
    border-radius: 50%;
    filter: blur(4px);
}
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
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">Mudah &amp; Cepat</span>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                Cara Kerja
                <span class="relative inline-block text-primary-600">
                    Niat Zakat
                    {{-- SVG underline — draw animasi saat section masuk viewport --}}
                    <svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11" preserveAspectRatio="none">
                        <path class="ck-underline-path" id="ckUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6" />
                    </svg>
                </span>
            </h2>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">Mulai kelola zakat lembaga Anda hanya dalam 3 langkah</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-12 md:space-y-0">

            {{-- ─────────── Step 1 ─────────── --}}
            <div class="flex flex-col md:flex-row items-center gap-6 md:gap-12">
                <div class="flex-shrink-0 flex flex-col items-center md:items-end w-full md:w-1/2 md:pr-6 nz-reveal-left">
                    <div class="flex items-center gap-4 md:flex-row-reverse">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz">
                            <span class="text-2xl font-black text-white">1</span>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end"><span class="how-dot"></span><span class="how-dot-sm"></span></div>
                    </div>
                </div>

                {{-- Card 1 : Karakter pendaftar berkacamata dengan laptop & tanda centang --}}
                <div class="step-card flex-1 bg-primary-50 rounded-2xl p-7 border border-primary-100 md:w-1/2 nz-reveal-right" style="min-height:130px; padding-right:96px;">
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Daftar &amp; Verifikasi</h3>
                    <p class="text-neutral-600 leading-relaxed">Daftarkan lembaga atau masjid Anda. Tim kami akan memverifikasi dan mengaktifkan akun dalam 1&times;24 jam.</p>

                    <div class="ck-char-wrap">
                        <svg viewBox="0 0 76 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Laptop base -->
                            <rect x="8"  y="62" width="52" height="26" rx="3" fill="#bbf7d0"/>
                            <rect x="11" y="65" width="46" height="21" rx="2" fill="white"/>
                            <!-- Screen lines -->
                            <rect x="14" y="68" width="18" height="2" rx="1" fill="#16a34a" opacity="0.35"/>
                            <rect x="14" y="72" width="12" height="2" rx="1" fill="#16a34a" opacity="0.25"/>
                            <rect x="14" y="76" width="16" height="2" rx="1" fill="#16a34a" opacity="0.25"/>
                            <!-- Verified badge (animated pop-in) -->
                            <g style="animation: ckPop 0.7s cubic-bezier(0.34,1.56,0.64,1) 1.0s both; transform-origin: 48px 72px;">
                                <circle cx="48" cy="72" r="8" fill="#16a34a"/>
                                <path d="M44 72l3 3 5-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </g>
                            <!-- Laptop hinge line -->
                            <rect x="6" y="87" width="60" height="4" rx="2" fill="#16a34a" opacity="0.15"/>

                            <!-- Torso -->
                            <path d="M22 80 Q38 68 54 80 L54 90 Q38 85 22 90Z" fill="#16a34a" opacity="0.75"/>
                            <!-- Left arm -->
                            <line x1="22" y1="74" x2="13" y2="82" stroke="#fde68a" stroke-width="5.5" stroke-linecap="round"/>
                            <!-- Right arm -->
                            <line x1="54" y1="74" x2="63" y2="82" stroke="#fde68a" stroke-width="5.5" stroke-linecap="round"/>

                            <!-- Head -->
                            <ellipse cx="38" cy="42" rx="12" ry="13" fill="#fde68a"/>
                            <!-- Hair -->
                            <ellipse cx="38" cy="30" rx="12" ry="6.5" fill="#78350f"/>
                            <!-- Glasses -->
                            <rect x="27" y="40" width="9"  height="6" rx="2.5" stroke="#374151" stroke-width="1.4" fill="rgba(200,230,255,0.3)"/>
                            <rect x="40" y="40" width="9"  height="6" rx="2.5" stroke="#374151" stroke-width="1.4" fill="rgba(200,230,255,0.3)"/>
                            <line x1="36" y1="43" x2="40" y2="43" stroke="#374151" stroke-width="1.3"/>
                            <!-- Eyes (blinking behind glasses) -->
                            <g style="animation: ckBlink 3.5s ease-in-out infinite;">
                                <ellipse cx="31.5" cy="43" rx="1.8" ry="2" fill="#1c1917"/>
                                <ellipse cx="44.5" cy="43" rx="1.8" ry="2" fill="#1c1917"/>
                            </g>
                            <!-- Smile -->
                            <path d="M32 49 Q38 54 44 49" stroke="#92400e" stroke-width="1.6" stroke-linecap="round" fill="none"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex justify-center">
                <div class="w-px h-12 bg-gradient-to-b from-primary-300 to-primary-100 opacity-60"></div>
            </div>

            {{-- ─────────── Step 2 ─────────── --}}
            <div class="flex flex-col md:flex-row-reverse items-center gap-6 md:gap-12">
                <div class="flex-shrink-0 flex flex-col items-center md:items-start w-full md:w-1/2 md:pl-6 nz-reveal-right">
                    <div class="flex items-center gap-4">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz" style="animation-delay:0.7s">
                            <span class="text-2xl font-black text-white">2</span>
                        </div>
                        <div class="flex flex-col gap-2"><span class="how-dot"></span><span class="how-dot-sm"></span></div>
                    </div>
                </div>

                {{-- Card 2 : Karakter teknisi memegang kunci putar, gear berputar --}}
                <div class="step-card flex-1 bg-primary-50 rounded-2xl p-7 border border-primary-100 md:w-1/2 nz-reveal-left" style="min-height:130px; padding-right:96px;">
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Setup &amp; Konfigurasi</h3>
                    <p class="text-neutral-600 leading-relaxed">Atur jenis zakat, kategori mustahik, data amil, dan konfigurasi masjid sesuai kebutuhan lembaga Anda.</p>

                    <div class="ck-char-wrap" style="animation-delay:0.5s;">
                        <svg viewBox="0 0 76 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Spinning gear -->
                            <g style="transform-origin:38px 76px; animation: ckSpin 6s linear infinite;">
                                <circle cx="38" cy="76" r="11" fill="none" stroke="#16a34a" stroke-width="3" opacity="0.6"/>
                                <circle cx="38" cy="76" r="4"  fill="#16a34a" opacity="0.55"/>
                                <!-- 8 teeth -->
                                <rect x="36.5" y="63" width="3" height="4.5" rx="1.2" fill="#16a34a" opacity="0.6"/>
                                <rect x="36.5" y="84.5" width="3" height="4.5" rx="1.2" fill="#16a34a" opacity="0.6"/>
                                <rect x="25"   y="74.5" width="4.5" height="3" rx="1.2" fill="#16a34a" opacity="0.6"/>
                                <rect x="46.5" y="74.5" width="4.5" height="3" rx="1.2" fill="#16a34a" opacity="0.6"/>
                                <rect x="28.5" y="66.5" width="3" height="4.5" rx="1.2" fill="#16a34a" opacity="0.5" transform="rotate(45 30 68.8)"/>
                                <rect x="46.5" y="64.5" width="3" height="4.5" rx="1.2" fill="#16a34a" opacity="0.5" transform="rotate(-45 48 66.8)"/>
                                <rect x="28.5" y="77.5" width="3" height="4.5" rx="1.2" fill="#16a34a" opacity="0.5" transform="rotate(-45 30 79.8)"/>
                                <rect x="46.5" y="77.5" width="3" height="4.5" rx="1.2" fill="#16a34a" opacity="0.5" transform="rotate(45 48 79.8)"/>
                            </g>

                            <!-- Torso / shirt -->
                            <path d="M23 77 Q38 67 53 77 L53 90 Q38 83 23 90Z" fill="#0d9488" opacity="0.8"/>
                            <!-- Left arm (holding wrench) -->
                            <line x1="23" y1="71" x2="12" y2="78" stroke="#fde68a" stroke-width="5.5" stroke-linecap="round"/>
                            <rect x="4" y="76" width="11" height="4" rx="2" fill="#6b7280" transform="rotate(30 9.5 78)"/>
                            <circle cx="6" cy="80" r="3" fill="none" stroke="#6b7280" stroke-width="1.8"/>
                            <!-- Right arm -->
                            <line x1="53" y1="71" x2="64" y2="78" stroke="#fde68a" stroke-width="5.5" stroke-linecap="round"/>

                            <!-- Head -->
                            <ellipse cx="38" cy="43" rx="12" ry="13" fill="#fde68a"/>
                            <!-- Hard hat -->
                            <path d="M26 42 Q38 26 50 42Z" fill="#0d9488"/>
                            <rect x="24" y="41" width="28" height="5" rx="0" fill="#0d9488"/>
                            <rect x="22" y="45" width="32" height="3" rx="1.5" fill="#0f766e"/>
                            <!-- Eyes (focused) -->
                            <g style="animation: ckBlink 4.5s ease-in-out 1s infinite;">
                                <ellipse cx="33" cy="45" rx="2" ry="2.2" fill="#1c1917"/>
                                <ellipse cx="43" cy="45" rx="2" ry="2.2" fill="#1c1917"/>
                            </g>
                            <!-- Determined mouth -->
                            <path d="M34 50 Q38 52 42 50" stroke="#92400e" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex justify-center">
                <div class="w-px h-12 bg-gradient-to-b from-primary-300 to-primary-100 opacity-60"></div>
            </div>

            {{-- ─────────── Step 3 ─────────── --}}
            <div class="flex flex-col md:flex-row items-center gap-6 md:gap-12">
                <div class="flex-shrink-0 flex flex-col items-center md:items-end w-full md:w-1/2 md:pr-6 nz-reveal-left">
                    <div class="flex items-center gap-4 md:flex-row-reverse">
                        <div class="how-num-badge w-16 h-16 bg-primary-600 rounded-2xl flex items-center justify-center shadow-nz" style="animation-delay:1.4s">
                            <span class="text-2xl font-black text-white">3</span>
                        </div>
                        <div class="flex flex-col gap-2 md:items-end"><span class="how-dot"></span><span class="how-dot-sm"></span></div>
                    </div>
                </div>

                {{-- Card 3 : Karakter merayakan dengan bar chart naik --}}
                <div class="step-card flex-1 bg-primary-50 rounded-2xl p-7 border border-primary-100 md:w-1/2 nz-reveal-right" style="min-height:130px; padding-right:96px;">
                    <h3 class="text-xl font-bold text-neutral-900 mb-3">Kelola &amp; Laporkan</h3>
                    <p class="text-neutral-600 leading-relaxed">Mulai catat penerimaan, salurkan ke mustahik, dan <em>generate</em> laporan konsolidasi kapan saja — transparan dan akuntabel.</p>

                    <div class="ck-char-wrap" style="animation-delay:0.9s;">
                        <svg viewBox="0 0 76 92" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Bar chart rising -->
                            <rect x="6"  y="80" width="9"  height="8"  rx="1.5" fill="#16a34a" opacity="0.30"/>
                            <rect x="18" y="72" width="9"  height="16" rx="1.5" fill="#16a34a" opacity="0.45"/>
                            <rect x="30" y="64" width="9"  height="24" rx="1.5" fill="#16a34a" opacity="0.60"/>
                            <rect x="42" y="56" width="9"  height="32" rx="1.5" fill="#16a34a" opacity="0.80"/>
                            <!-- Trend line -->
                            <polyline points="10,78 22,70 34,62 46,54" stroke="#16a34a" stroke-width="1.8" stroke-linecap="round" fill="none" stroke-dasharray="3 4" opacity="0.7"/>
                            <!-- X axis -->
                            <line x1="4" y1="89" x2="55" y2="89" stroke="#16a34a" stroke-width="1.5" stroke-linecap="round" opacity="0.25"/>

                            <!-- Body -->
                            <path d="M47 77 Q60 67 73 77 L73 90 Q60 83 47 90Z" fill="#16a34a" opacity="0.8"/>
                            <!-- Waving arm right -->
                            <g style="transform-origin: 73px 72px; animation: ckWave 2s ease-in-out infinite;">
                                <line x1="73" y1="70" x2="80" y2="60" stroke="#fde68a" stroke-width="5.5" stroke-linecap="round"/>
                            </g>
                            <!-- Arm left -->
                            <line x1="47" y1="72" x2="38" y2="68" stroke="#fde68a" stroke-width="5.5" stroke-linecap="round"/>

                            <!-- Head -->
                            <ellipse cx="60" cy="46" rx="12" ry="13" fill="#fde68a"/>
                            <!-- Hair -->
                            <path d="M48 43 Q60 29 72 43 Q72 36 60 32 Q48 36 48 43Z" fill="#78350f"/>
                            <!-- Happy squint eyes -->
                            <path d="M55 46 Q57 44 59 46" stroke="#1c1917" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                            <path d="M61 46 Q63 44 65 46" stroke="#1c1917" stroke-width="1.8" stroke-linecap="round" fill="none"/>
                            <!-- Big smile -->
                            <path d="M54 51 Q60 58 66 51" stroke="#92400e" stroke-width="2" stroke-linecap="round" fill="none"/>
                            <!-- Blush -->
                            <ellipse cx="53" cy="50" rx="3.5" ry="2.5" fill="#fca5a5" opacity="0.45"/>
                            <ellipse cx="67" cy="50" rx="3.5" ry="2.5" fill="#fca5a5" opacity="0.45"/>
                            <!-- Sparkles -->
                            <text x="3"  y="56" font-size="11" fill="#fbbf24" style="animation: ckPop 2.2s ease-in-out 0s infinite;">✦</text>
                            <text x="62" y="26" font-size="9"  fill="#fbbf24" style="animation: ckPop 2.2s ease-in-out 0.7s infinite;">✦</text>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Underline draw animasi saat section masuk viewport ──
    const underlinePath = document.getElementById('ckUnderlinePath');
    if (underlinePath) {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    underlinePath.classList.add('ck-draw');
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        obs.observe(document.getElementById('cara-kerja'));
    }

    // ── Reveal observer (guard agar tidak double-init) ──
    if (!window._nzRevealInit) {
        window._nzRevealInit = true;
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('nz-visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.nz-reveal, .nz-reveal-left, .nz-reveal-right').forEach(el => revealObs.observe(el));
    }
});
</script>