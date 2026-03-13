{{-- ============================================================
     SECTION: VIDEO — Tata Cara Penggunaan Niat Zakat
     resources/views/partials/landing/sections/video.blade.php

     Layout: 2 kolom — kiri video card, kanan teks + CTA
     Style konsisten dengan section lain (cara-kerja, fitur, dll)
     ============================================================ --}}

<style>
/* ── Underline draw ── */
.vd-underline-path {
    fill: none;
    stroke: #16a34a;
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-dasharray: 260;
    stroke-dashoffset: 260;
}
.vd-underline-path.vd-draw {
    animation: vdDrawLine 1.1s cubic-bezier(0.4, 0, 0.2, 1) 0.3s forwards;
}
@keyframes vdDrawLine {
    from { stroke-dashoffset: 260; }
    to   { stroke-dashoffset: 0; }
}

/* ── Background floaters ── */
@keyframes vdFloatA { 0%,100%{transform:rotate(12deg) translateY(0px)}  50%{transform:rotate(12deg) translateY(-9px)} }
@keyframes vdFloatB { 0%,100%{transform:rotate(-8deg) translateY(0px)}  50%{transform:rotate(-8deg) translateY(-7px)} }
@keyframes vdFloatC { 0%,100%{transform:rotate(5deg) translateY(0px)}   50%{transform:rotate(5deg) translateY(-11px)} }
@keyframes vdFloatD { 0%,100%{transform:rotate(-14deg) translateY(0px)} 50%{transform:rotate(-14deg) translateY(-6px)} }
.vd-fb-a { animation: vdFloatA 5s ease-in-out infinite; }
.vd-fb-b { animation: vdFloatB 6s ease-in-out 0.8s infinite; }
.vd-fb-c { animation: vdFloatC 7s ease-in-out 1.6s infinite; }
.vd-fb-d { animation: vdFloatD 5.5s ease-in-out 0.4s infinite; }

/* ── Video card kiri ── */
.vd-card {
    position: relative;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow:
        0 20px 60px rgba(22, 163, 74, 0.18),
        0 4px 16px rgba(0, 0, 0, 0.10);
    background: #000;
    transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.35s ease;
}
.vd-card:hover {
    transform: translateY(-6px);
    box-shadow:
        0 28px 72px rgba(22, 163, 74, 0.26),
        0 6px 20px rgba(0, 0, 0, 0.12);
}

/* Media area */
.vd-media-wrap {
    position: relative;
    aspect-ratio: 16 / 9;
    width: 100%;
    background: #111;
    overflow: hidden;
}
.vd-media-wrap iframe,
.vd-media-wrap video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    border: none;
}
.vd-thumbnail {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 1;
    transition: opacity 0.3s ease;
}
.vd-thumbnail.vd-hidden { opacity: 0; pointer-events: none; }

/* Play overlay */
.vd-play-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.30);
    cursor: pointer;
    z-index: 3;
    transition: background 0.25s ease, opacity 0.3s ease;
}
.vd-play-overlay:hover { background: rgba(0, 0, 0, 0.18); }
.vd-play-overlay.vd-hidden { opacity: 0; pointer-events: none; }

.vd-play-btn {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #22c55e, #15803d);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 28px rgba(22, 163, 74, 0.55);
    transition: transform 0.2s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.2s ease;
}
.vd-play-overlay:hover .vd-play-btn {
    transform: scale(1.12);
    box-shadow: 0 10px 36px rgba(22, 163, 74, 0.65);
}
.vd-play-btn svg {
    width: 26px;
    height: 26px;
    fill: white;
    margin-left: 3px;
}

/* Caption bar hijau di bawah video (seperti referensi) */
.vd-caption-bar {
    background: linear-gradient(135deg, #1a5c2a 0%, #15803d 50%, #166534 100%);
    padding: 1.1rem 1.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.vd-caption-bar::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 60% at 20% 40%, rgba(255,255,255,0.08) 0%, transparent 70%);
    pointer-events: none;
}
.vd-caption-bar p {
    color: rgba(255, 255, 255, 0.95);
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.5;
    position: relative;
    z-index: 1;
    margin: 0;
}

/* ── Kolom kanan ── */
.vd-content-title {
    font-size: clamp(1.75rem, 3vw, 2.5rem);
    font-weight: 800;
    color: #111827;
    line-height: 1.2;
    margin-bottom: 1.25rem;
    text-align: justify;
}
.vd-content-desc {
    font-size: 1rem;
    color: #4b5563;
    line-height: 1.8;
    margin-bottom: 1.75rem;
    text-align: justify;
}

/* Poin fitur */
.vd-feature-list {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
    margin-bottom: 2rem;
    padding: 0;
    list-style: none;
}
.vd-feature-item {
    display: flex;
    align-items: center;
    gap: 0.65rem;
    font-size: 0.9rem;
    color: #374151;
    font-weight: 500;
}
.vd-feature-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #16a34a;
    flex-shrink: 0;
}

/* CTA button */
.vd-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 60%, #15803d 100%);
    color: #fff;
    font-weight: 700;
    font-size: 0.95rem;
    padding: 0.85rem 1.75rem;
    border-radius: 0.75rem;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 4px 20px rgba(22, 163, 74, 0.35);
    transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.25s ease;
}
.vd-cta-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(22, 163, 74, 0.48);
    color: #fff;
    text-decoration: none;
}
.vd-cta-btn svg {
    width: 18px;
    height: 18px;
    stroke: white;
    transition: transform 0.2s ease;
}
.vd-cta-btn:hover svg { transform: translateX(3px); }
</style>

<section id="video-panduan" class="relative py-20 bg-white overflow-hidden">

    {{-- Background dekoratif --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="vd-diag-pat" x="0" y="0" width="56" height="56" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="56" x2="56" y2="0" stroke="rgba(45,105,54,0.055)" stroke-width="1" stroke-dasharray="4 7"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#vd-diag-pat)"/>
        </svg>
        <div class="vd-fb-a absolute rounded-2xl border-2" style="width:88px;height:88px;top:7%;left:4%;border-color:rgba(45,105,54,0.12);background:rgba(45,105,54,0.025);transform:rotate(12deg);"></div>
        <div class="vd-fb-b absolute rounded-xl border-2"  style="width:60px;height:60px;top:15%;right:6%;border-color:rgba(45,105,54,0.09);background:rgba(45,105,54,0.02);transform:rotate(-8deg);"></div>
        <div class="vd-fb-c absolute rounded-2xl border"   style="width:110px;height:110px;bottom:10%;left:2%;border-color:rgba(45,105,54,0.08);background:rgba(45,105,54,0.015);transform:rotate(5deg);"></div>
        <div class="vd-fb-d absolute rounded-xl border"    style="width:68px;height:68px;bottom:18%;right:4%;border-color:rgba(45,105,54,0.1);background:rgba(45,105,54,0.02);transform:rotate(-14deg);"></div>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 65% 70% at 50% 50%, rgba(255,255,255,0.78) 0%, transparent 100%);"></div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">

        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

            {{-- ═══════ KOLOM KIRI — Video Card ═══════ --}}
            <div class="w-full lg:w-1/2 nz-reveal">
                <div class="vd-card">

                    <div class="vd-media-wrap">
                        {{-- Thumbnail --}}
                        <img
                            id="vdThumbnail"
                            class="vd-thumbnail"
                            src="https://img.youtube.com/vi/wq6x55KeVng/maxresdefault.jpg"
                            alt="Thumbnail panduan penggunaan Niat Zakat"
                            onerror="this.src='https://img.youtube.com/vi/wq6x55KeVng/hqdefault.jpg'"
                        />

                        {{-- OPSI A: YouTube (lazy load) — ganti VIDEO_ID --}}
                        <iframe
                            id="vdIframe"
                            src=""
                            data-src="https://www.youtube.com/embed/wq6x55KeVng?autoplay=1&rel=0&modestbranding=1&start=84"
                            allow="autoplay; encrypted-media; picture-in-picture"
                            allowfullscreen
                            title="Panduan penggunaan Niat Zakat"
                            style="display:none;"
                        ></iframe>

                        {{--
                            OPSI B: Video lokal — uncomment & comment iframe di atas
                            <video id="vdVideo" src="{{ asset('videos/panduan.mp4') }}"
                                poster="{{ asset('images/video-thumbnail.jpg') }}"
                                controls preload="metadata" style="display:none;"></video>
                        --}}

                        {{-- Play overlay --}}
                        <div class="vd-play-overlay" id="vdPlayOverlay" role="button" aria-label="Putar video panduan">
                            <div class="vd-play-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M8 5.14v14l11-7-11-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>{{-- /.vd-media-wrap --}}

                    {{-- Caption bar hijau --}}
                    <div class="vd-caption-bar">
                        <p>Tata Cara Zakat Fitrah Lengkap dengan Hukum, Niat, Waktu dan Doanya</p>
                    </div>

                </div>{{-- /.vd-card --}}
            </div>


            {{-- ═══════ KOLOM KANAN — Teks & CTA ═══════ --}}
            <div class="w-full lg:w-1/2 nz-reveal" style="transition-delay: 150ms;">

                <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-5">
                    Tutorial Ibadah
                </span>

                <h2 class="vd-content-title">
                    Tata Cara
                    <span class="relative inline-block text-primary-600">
                        Zakat Fitrah
                        <svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11" preserveAspectRatio="none">
                            <path class="vd-underline-path" id="vdUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6"/>
                        </svg>
                    </span>
                    <span style="display:block;font-size:clamp(1rem,2vw,1.35rem);color:#374151;font-weight:700;margin-top:0.3rem;text-align:left;">Lengkap dengan Hukum, Niat, Waktu &amp; Doanya</span>
                </h2>

                <p class="vd-content-desc">
                    Pahami kewajiban zakat fitrah secara menyeluruh — dari dasar hukumnya, bacaan niat yang benar,
                    waktu yang tepat untuk menunaikannya, hingga doa setelah berzakat. Semua dibahas tuntas dalam video ini.
                </p>

                <a href="{{ route('register') }}" class="vd-cta-btn">
                    Tunaikan Zakat Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>

            </div>{{-- /.kolom kanan --}}

        </div>{{-- /.flex --}}
    </div>
</section>

<script>
(function () {
    var section   = document.getElementById('video-panduan');
    var ulPath    = document.getElementById('vdUnderlinePath');
    var overlay   = document.getElementById('vdPlayOverlay');
    var iframe    = document.getElementById('vdIframe');
    var thumbnail = document.getElementById('vdThumbnail');

    /* SVG underline draw */
    if (section && ulPath && 'IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) { ulPath.classList.add('vd-draw'); }
            });
        }, { threshold: 0.4 }).observe(section);
    } else if (ulPath) {
        ulPath.classList.add('vd-draw');
    }

    /* Play overlay → lazy load iframe */
    if (overlay && iframe) {
        overlay.addEventListener('click', function () {
            if (!iframe.src || iframe.src === window.location.href) {
                iframe.src = iframe.getAttribute('data-src') || '';
            }
            iframe.style.display = 'block';
            overlay.classList.add('vd-hidden');
            if (thumbnail) thumbnail.classList.add('vd-hidden');
        });
    }

    /* OPSI B — video lokal: uncomment
    var videoEl = document.getElementById('vdVideo');
    if (overlay && videoEl) {
        overlay.addEventListener('click', function () {
            videoEl.style.display = 'block';
            overlay.classList.add('vd-hidden');
            if (thumbnail) thumbnail.classList.add('vd-hidden');
            videoEl.play();
        });
    }
    */
})();
</script>