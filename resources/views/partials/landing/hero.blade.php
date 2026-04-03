{{-- partials/landing/hero.blade.php --}}
<section class="relative bg-white overflow-hidden" style="min-height: 100svh;">

    {{-- Background Image --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('image/tangan.jpg') }}" alt=""
             class="w-full h-full object-cover object-center" aria-hidden="true">
        <div class="absolute inset-0"
             style="background: linear-gradient(to right,
                    rgba(255,255,255,0.92) 0%,
                    rgba(255,255,255,0.85) 40%,
                    rgba(255,255,255,0.2) 100%);">
        </div>
    </div>

    {{-- DOT GRID DEKORASI --}}
    <div class="absolute bottom-16 left-8 z-10 opacity-25 pointer-events-none"
         style="width:120px;height:120px;background-image:radial-gradient(#2d6936 2.2px,transparent 2.2px);background-size:16px 16px;"></div>
    <div class="absolute top-24 right-4 z-10 opacity-20 pointer-events-none"
         style="width:100px;height:100px;background-image:radial-gradient(#2d6936 2px,transparent 2px);background-size:14px 14px;"></div>
    <div class="absolute top-1/3 left-0 z-10 opacity-20 pointer-events-none"
         style="width:80px;height:160px;background-image:radial-gradient(#2d6936 2px,transparent 2px);background-size:16px 16px;"></div>
    <div class="absolute top-6 left-1/3 z-10 opacity-15 pointer-events-none"
         style="width:220px;height:60px;background-image:radial-gradient(#94a3b8 1.8px,transparent 1.8px);background-size:18px 18px;"></div>
    <div class="absolute top-1/2 right-2 z-10 opacity-18 pointer-events-none"
         style="width:90px;height:90px;background-image:radial-gradient(#94a3b8 2px,transparent 2px);background-size:15px 15px;"></div>

    {{-- Konten utama --}}
    <div class="relative z-20 flex items-center w-full px-4 sm:px-10 lg:px-20"
         style="min-height:100svh; padding-top:100px; padding-bottom:100px;">

        <div class="relative w-full flex flex-col lg:flex-row items-center gap-16">

            {{-- KONTEN KIRI --}}
            <div class="w-full lg:w-[55%] text-left order-2 lg:order-1">
                <h1 class="font-black text-slate-900 tracking-tight mb-6"
                    style="font-size:clamp(2.5rem,4.5vw,3.8rem); line-height:1.1;">
                    <span class="hero-reveal block" style="transition-delay:0ms;">Kelola Zakat</span>
                    <span class="hero-reveal block text-primary-600" style="transition-delay:120ms;">Lebih Modern,</span>
                    <span class="hero-reveal relative inline-block" style="transition-delay:240ms;">
                        <span id="hero-line3"></span><span id="hero-cursor" class="hero-cursor"></span>
                        <svg class="absolute left-0 w-full pointer-events-none"
                             style="bottom:-16px; height:20px; overflow:visible;"
                             viewBox="0 0 300 20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                            <path id="heroUnderlinePath1" class="hero-underline-path"
                                d="M3,13 C80,4 220,18 297,11"
                                fill="none" stroke="#2d6936" stroke-width="2.8"
                                stroke-linecap="round" stroke-linejoin="round"/>
                            <path id="heroUnderlinePath2" class="hero-underline-path-2"
                                d="M3,17 C80,8 220,22 297,15"
                                fill="none" stroke="#86efac" stroke-width="1.4"
                                stroke-linecap="round" stroke-linejoin="round" opacity="0.55"/>
                        </svg>
                    </span>
                </h1>

                <p class="hero-reveal text-slate-700 text-base sm:text-lg font-medium leading-relaxed mb-12 max-w-lg"
                   style="margin-top:1.5rem; transition-delay:360ms;">
                    Transformasi digital untuk lembaga amil zakat dan masjid.
                    Kelola transparansi laporan secara real-time dalam satu dashboard terintegrasi.
                </p>

                {{-- BUTTONS: selalu berdampingan kiri-kanan di semua ukuran --}}
                <div class="hero-reveal" style="transition-delay:480ms; display:flex; flex-direction:row; gap:0.75rem; align-items:center; flex-wrap:nowrap;">
                    <a href="{{ route('register') }}"
                       style="display:inline-flex; align-items:center; justify-content:center; gap:0.4rem; padding:0.75rem 1.1rem; background:#16a34a; color:#fff; font-weight:700; font-size:0.82rem; border-radius:1rem; box-shadow:0 8px 24px rgba(22,163,74,0.25); text-decoration:none; white-space:nowrap; flex-shrink:0; transition:background .2s, transform .1s;"
                       onmouseover="this.style.background='#15803d'" onmouseout="this.style.background='#16a34a'"
                       onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform='scale(1)'">
                        Mulai Gratis Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;flex-shrink:0;" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    <a href="#fitur"
                       style="display:inline-flex; align-items:center; justify-content:center; padding:0.75rem 1.1rem; background:rgba(255,255,255,0.9); color:#334155; font-weight:700; font-size:0.82rem; border-radius:1rem; border:1.5px solid #e2e8f0; text-decoration:none; white-space:nowrap; flex-shrink:0; transition:border-color .2s, color .2s, transform .1s; backdrop-filter:blur(4px);"
                       onmouseover="this.style.borderColor='#16a34a';this.style.color='#16a34a'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#334155'"
                       onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform='scale(1)'">
                        Pelajari Fitur
                    </a>
                </div>
            </div>

            {{-- FOTO KANAN: disembunyikan di mobile, tampil di lg ke atas --}}
            <div class="w-full lg:w-[45%] order-1 lg:order-2 hero-reveal hidden lg:flex justify-center lg:justify-end"
                 style="transition-delay:200ms;">

                {{-- Parallax wrapper --}}
                <div id="hero-parallax" style="position:relative; width:440px; max-width:100%;">

                    {{-- Glow blob --}}
                    <div class="hero-glow-blob" aria-hidden="true"></div>

                    {{-- Ring 1: luar, searah --}}
                    <svg class="hero-ring hero-ring-1" viewBox="0 0 540 540" fill="none" aria-hidden="true">
                        <circle cx="270" cy="270" r="256"
                                stroke="#16a34a" stroke-width="3"
                                stroke-dasharray="20 9"
                                stroke-linecap="round" opacity="0.80"/>
                    </svg>

                    {{-- Ring 2: tengah, berlawanan --}}
                    <svg class="hero-ring hero-ring-2" viewBox="0 0 540 540" fill="none" aria-hidden="true">
                        <circle cx="270" cy="270" r="238"
                                stroke="#22c55e" stroke-width="2.5"
                                stroke-dasharray="5 16"
                                stroke-linecap="round" opacity="0.65"/>
                    </svg>

                    {{-- Ring 3: dalam, searah lebih cepat --}}
                    <svg class="hero-ring hero-ring-3" viewBox="0 0 540 540" fill="none" aria-hidden="true">
                        <circle cx="270" cy="270" r="218"
                                stroke="#86efac" stroke-width="3.5"
                                stroke-dasharray="2 12"
                                stroke-linecap="round" opacity="0.60"/>
                    </svg>

                    {{-- Arc dekoratif kanan atas --}}
                    <svg class="absolute z-0 pointer-events-none"
                         style="width:200px;height:200px;top:-30px;right:-40px;opacity:0.15;"
                         viewBox="0 0 200 200" fill="none">
                        <path d="M20 180 Q100 10, 180 100" stroke="#2d6936" stroke-width="2" stroke-linecap="round"/>
                        <path d="M36 186 Q116 16, 186 110" stroke="#86efac" stroke-width="1.2" stroke-linecap="round"/>
                    </svg>

                    {{-- Arc dekoratif kiri bawah --}}
                    <svg class="absolute z-0 pointer-events-none"
                         style="width:140px;height:140px;bottom:-24px;left:-30px;opacity:0.14;"
                         viewBox="0 0 140 140" fill="none">
                        <path d="M120 18 Q18 18, 18 120" stroke="#2d6936" stroke-width="2" stroke-linecap="round"/>
                        <path d="M112 28 Q28 28, 28 112" stroke="#86efac" stroke-width="1" stroke-linecap="round"/>
                    </svg>

                    {{-- Dot grids --}}
                    <div class="absolute z-0 opacity-55"
                         style="width:110px;height:110px;top:-28px;left:-28px;
                                background-image:radial-gradient(#2d6936 2.2px,transparent 2.2px);
                                background-size:16px 16px;"></div>
                    <div class="absolute z-0 opacity-50"
                         style="width:130px;height:130px;bottom:-28px;right:-28px;
                                background-image:radial-gradient(#2d6936 2.2px,transparent 2.2px);
                                background-size:16px 16px;"></div>

                    <div id="hero-photo-wrap" style="position:relative; z-index:10;">

                        <div class="hero-img-frame">
                            <img src="{{ asset('image/zakat.jpg') }}"
                                 alt="Visual Zakat"
                                 class="w-full object-cover aspect-square"
                                 style="display:block;">
                        </div>

                        <canvas id="hero-glow-canvas"
                                aria-hidden="true"
                                style="position:absolute;
                                       pointer-events:none;
                                       z-index:30;">
                        </canvas>

                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<style>
/* ── Hero Reveal ── */
.hero-reveal {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                transform 1.1s cubic-bezier(0.16, 1, 0.3, 1);
}
.hero-reveal.is-visible { opacity: 1; transform: translateY(0); }

/* ── Kursor ketik ── */
.hero-cursor {
    display: inline-block;
    width: 2.5px; height: 0.8em;
    background: #16a34a;
    margin-left: 1px; vertical-align: middle;
    border-radius: 2px; opacity: 1;
}
.hero-cursor.blink  { animation: heroBlink 0.9s step-end infinite; }
.hero-cursor.hidden { opacity:0 !important; animation:none; }
@keyframes heroBlink { 0%,100%{opacity:1} 50%{opacity:0} }

/* ── Garis bawah ── */
.hero-underline-path,
.hero-underline-path-2 {
    stroke-dasharray:310; stroke-dashoffset:310;
    opacity:0; transition:opacity .4s ease;
}
.hero-underline-path.is-drawn {
    transition:none;
    animation: heroLineDraw .95s cubic-bezier(.25,.46,.45,.94) forwards,
               heroLineBreath 5s ease-in-out 1.2s infinite;
}
.hero-underline-path-2.is-drawn {
    transition:none;
    animation: heroLineDraw 1.15s cubic-bezier(.25,.46,.45,.94) .18s forwards;
}
.hero-underline-path.is-hiding,
.hero-underline-path-2.is-hiding {
    animation:none !important; opacity:0 !important;
    transition:opacity .35s ease !important;
}
@keyframes heroLineDraw {
    0%  { stroke-dashoffset:310; opacity:0; }
    8%  { opacity:1; }
    100%{ stroke-dashoffset:0;   opacity:1; }
}
@keyframes heroLineBreath { 0%,100%{opacity:1} 50%{opacity:.4} }

/* ── Frame foto ── */
.hero-img-frame {
    position: relative;
    z-index: 10;
    border-radius: 2.2rem;
    overflow: hidden;
    border: 10px solid rgba(255,255,255,0.92);
    box-shadow:
        0 0 0 1px rgba(22,163,74,0.14),
        0 20px 60px rgba(22,163,74,0.20),
        0 6px 20px rgba(0,0,0,0.08);
    transition: box-shadow .5s ease;
}
.hero-img-frame:hover {
    box-shadow:
        0 0 0 2px rgba(22,163,74,0.28),
        0 28px 72px rgba(22,163,74,0.28),
        0 8px 28px rgba(0,0,0,0.10);
}

/* ── Glow blob ── */
.hero-glow-blob {
    position:absolute; z-index:0;
    top:50%; left:50%;
    width:115%; height:115%;
    transform:translate(-50%,-50%);
    border-radius:50%;
    background:radial-gradient(circle,
        rgba(34,197,94,0.20) 0%,
        rgba(22,163,74,0.10) 42%,
        transparent 70%);
    animation: heroGlowPulse 4s ease-in-out infinite;
    pointer-events:none;
}
@keyframes heroGlowPulse {
    0%,100%{ transform:translate(-50%,-50%) scale(1);    opacity:1;   }
    50%    { transform:translate(-50%,-50%) scale(1.09); opacity:0.65; }
}

/* ── Ring ── */
.hero-ring {
    position:absolute; top:50%; left:50%;
    z-index:1; pointer-events:none;
    width:116%; height:116%;
}
.hero-ring-1 { animation:heroRingCW  24s linear infinite; transform:translate(-50%,-50%); }
.hero-ring-2 { animation:heroRingCCW 17s linear infinite; transform:translate(-50%,-50%); }
.hero-ring-3 { animation:heroRingCW  11s linear infinite; transform:translate(-50%,-50%); }
@keyframes heroRingCW  { from{transform:translate(-50%,-50%) rotate(0deg)}   to{transform:translate(-50%,-50%) rotate(360deg)}  }
@keyframes heroRingCCW { from{transform:translate(-50%,-50%) rotate(0deg)}   to{transform:translate(-50%,-50%) rotate(-360deg)} }
</style>

<script>
(function () {
    var heroAnimated = false;
    var loopRunning  = false;

    var TEXT='Lebih Amanah.', TYPE_SPEED=68, DELETE_SPEED=32,
        PAUSE_TYPED=2600, PAUSE_DELETED=500;
    var elText=null, elCursor=null, p1=null, p2=null;

    function showLine() {
        [p1,p2].forEach(function(p){
            if(!p) return;
            p.classList.remove('is-hiding','is-drawn');
            p.style.strokeDashoffset='310'; p.style.opacity='0';
            void p.getBoundingClientRect();
            p.classList.add('is-drawn');
        });
    }
    function hideLine() {
        [p1,p2].forEach(function(p){
            if(!p) return;
            p.classList.remove('is-drawn');
            p.classList.add('is-hiding');
            setTimeout(function(){
                p.classList.remove('is-hiding');
                p.style.strokeDashoffset='310'; p.style.opacity='0';
            }, 380);
        });
    }

    function typeLoop() {
        if(loopRunning) return; loopRunning=true;
        function typeIn(i) {
            elText.textContent=TEXT.slice(0,i);
            if(i<TEXT.length){ setTimeout(function(){typeIn(i+1);},TYPE_SPEED); }
            else {
                elCursor.classList.remove('blink');
                setTimeout(function(){
                    showLine();
                    setTimeout(function(){
                        elCursor.classList.add('blink');
                        setTimeout(deleteOut,PAUSE_TYPED);
                    },200);
                },100);
            }
        }
        function deleteOut(){
            var len=elText.textContent.length;
            if(len===TEXT.length){ hideLine(); elCursor.classList.remove('blink'); elCursor.style.opacity='1'; }
            if(len>0){ elText.textContent=elText.textContent.slice(0,len-1); setTimeout(deleteOut,DELETE_SPEED); }
            else { setTimeout(function(){ elCursor.classList.add('blink'); setTimeout(function(){typeIn(0);},80); },PAUSE_DELETED); }
        }
        elText.textContent=''; elCursor.classList.add('blink'); typeIn(0);
    }

    /* ── Parallax scroll ── */
    function initParallax() {
        var wrap = document.getElementById('hero-parallax');
        if(!wrap) return;
        var ticking=false;
        window.addEventListener('scroll',function(){
            if(ticking) return; ticking=true;
            requestAnimationFrame(function(){
                var offset=(window.scrollY||window.pageYOffset)*0.18;
                wrap.style.transform='translateY(-'+offset+'px)';
                ticking=false;
            });
        },{passive:true});
    }

    function runHeroAnimations() {
        if(heroAnimated) return; heroAnimated=true;
        elText  =document.getElementById('hero-line3');
        elCursor=document.getElementById('hero-cursor');
        p1=document.getElementById('heroUnderlinePath1');
        p2=document.getElementById('heroUnderlinePath2');

        document.querySelectorAll('.hero-reveal').forEach(function(el){
            el.classList.add('is-visible');
        });

        setTimeout(typeLoop,700);
        initParallax();
    }

    document.addEventListener('splashHidden',function(){runHeroAnimations();});
    window.__onSplashHidden=window.__onSplashHidden||[];
    window.__onSplashHidden.push(function(){runHeroAnimations();});
    window.addEventListener('load',function(){
        setTimeout(function(){runHeroAnimations();},4700);
    });
})();
</script>