{{-- ============================================================
     SECTION: TESTIMONI
     resources/views/partials/landing/sections/testimoni.blade.php
     Background character: Horizontal line grid + wave SVG
     ============================================================ --}}

<style>
    /* ── UNDERLINE ANIMASI MEREKA — sekali saat scroll ── */
    .testi-underline-svg {
        display: block;
        width: 100%;
        height: 10px;
        overflow: visible;
        margin-top: 2px;
    }

    .testi-underline-path {
        fill: none;
        stroke: #16a34a;
        stroke-width: 3.5;
        stroke-linecap: round;
        stroke-dasharray: 300;
        stroke-dashoffset: 300;
    }

    .testi-underline-path.nz-draw {
        animation: drawUnderlineTesti 1.2s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        animation-delay: 0.3s;
    }

    @keyframes drawUnderlineTesti {
        from {
            stroke-dashoffset: 300;
        }

        to {
            stroke-dashoffset: 0;
        }
    }
</style>

<section id="testimoni" class="relative py-20 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        {{-- Horizontal line grid --}}
        <svg class="absolute inset-0 w-full h-full">
            <defs>
                <pattern id="hline-pat" x="0" y="0" width="100%" height="36" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="35.5" x2="100%" y2="35.5" stroke="rgba(45,105,54,0.045)"
                        stroke-width="1" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#hline-pat)" />
        </svg>
        {{-- Wave bottom --}}
        <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 180" preserveAspectRatio="none"
            style="height:180px;opacity:0.055;">
            <path fill="#2d6936"
                d="M0,96L60,90.7C120,85,240,75,360,80C480,85,600,107,720,106.7C840,107,960,85,1080,69.3C1200,53,1320,43,1380,37.3L1440,32L1440,180L0,180Z" />
        </svg>
        {{-- Wave top --}}
        <svg class="absolute top-0 right-0 w-full" viewBox="0 0 1440 140" preserveAspectRatio="none"
            style="height:140px;opacity:0.04;transform:scaleX(-1) scaleY(-1);">
            <path fill="#2d6936"
                d="M0,96L60,90.7C120,85,240,75,360,80C480,85,600,107,720,106.7C840,107,960,85,1080,69.3C1200,53,1320,43,1380,37.3L1440,32L1440,140L0,140Z" />
        </svg>
        <div class="absolute inset-0"
            style="background:radial-gradient(ellipse 80% 70% at 50% 45%, rgba(255,255,255,0.85) 0%, transparent 100%);">
        </div>
    </div>

    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20">

        {{-- ── HEADER — konsisten dengan section Fitur ── --}}
        <div class="text-center mb-16 nz-reveal">
            <span class="inline-block px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                Testimoni
            </span>

            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900 mb-4">
                Apa Kata
                <span class="relative inline-block text-primary-600 whitespace-nowrap">
                    Mereka?
                    <svg class="block w-full overflow-visible" style="height:11px;margin-top:3px;" viewBox="0 0 200 11"
                        preserveAspectRatio="none">
                        <path class="testi-underline-path" id="testiUnderlinePath" d="M2,7 Q50,2 100,7 Q150,12 198,6" />
                    </svg>
                </span>
            </h2>

            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Cerita inspiratif dari para muzaki yang telah mempercayakan zakatnya melalui platform kami
            </p>
        </div>

        @if (isset($testimonis) && $testimonis->isNotEmpty())
            {{-- Data dari database --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach ($testimonis as $i => $t)
                    <div class="nz-reveal" style="transition-delay: {{ $i * 0.1 }}s">
                        <div
                            class="relative bg-white rounded-2xl p-8 shadow-card hover:shadow-card-hover transition-all duration-300 h-full border border-neutral-100">
                            <div
                                class="absolute top-6 right-6 w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center opacity-50">
                                <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <div class="mb-6">
                                <div class="flex items-center space-x-1 mb-4">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <svg class="w-5 h-5 {{ $s <= $t->rating ? 'text-yellow-400' : 'text-gray-200' }} fill-current"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-neutral-700 leading-relaxed italic">"{{ $t->isi_testimoni }}"</p>
                            </div>
                            <div class="flex items-center space-x-4 pt-4 border-t border-neutral-100">
                                <div
                                    class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary-600 font-bold text-lg">{{ $t->inisial }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-neutral-900">{{ $t->nama_pengirim }}</h4>
                                    @if ($t->pekerjaan)
                                        <p class="text-sm text-neutral-500">{{ $t->pekerjaan }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback placeholder --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach ([['nama' => 'Ahmad Hidayat', 'pekerjaan' => 'Pengusaha', 'isi' => 'Platform yang sangat memudahkan! Saya bisa tracking zakat saya kemana disalurkan. Transparan dan terpercaya.', 'rating' => 5], ['nama' => 'Siti Fatimah', 'pekerjaan' => 'Profesional', 'isi' => 'Kalkulator zakatnya sangat membantu. Tidak perlu bingung lagi menghitung nisab dan kadar zakat. Recommended!', 'rating' => 5], ['nama' => 'Muhammad Rizki', 'pekerjaan' => 'Karyawan Swasta', 'isi' => 'Laporan penyalurannya detail banget. Saya jadi tau persis kemana zakat saya. Alhamdulillah merasa lebih tenang.', 'rating' => 5]] as $idx => $item)
                    <div class="nz-reveal" style="transition-delay: {{ $idx * 0.1 }}s">
                        <div
                            class="relative bg-white rounded-2xl p-8 shadow-card hover:shadow-card-hover transition-all duration-300 h-full border border-neutral-100">
                            <div
                                class="absolute top-6 right-6 w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center opacity-50">
                                <svg class="w-6 h-6 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <div class="mb-6">
                                <div class="flex items-center space-x-1 mb-4">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <svg class="w-5 h-5 {{ $s <= $item['rating'] ? 'text-yellow-400' : 'text-gray-200' }} fill-current"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                        </svg>
                                    @endfor
                                </div>
                                <p class="text-neutral-700 leading-relaxed italic">"{{ $item['isi'] }}"</p>
                            </div>
                            <div class="flex items-center space-x-4 pt-4 border-t border-neutral-100">
                                @php
                                    $inisial = strtoupper(
                                        substr($item['nama'], 0, 1) .
                                            (strpos($item['nama'], ' ') !== false
                                                ? substr($item['nama'], strpos($item['nama'], ' ') + 1, 1)
                                                : ''),
                                    );
                                @endphp
                                <div
                                    class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-primary-600 font-bold text-lg">{{ $inisial }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-neutral-900">{{ $item['nama'] }}</h4>
                                    <p class="text-sm text-neutral-500">{{ $item['pekerjaan'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Underline animasi "Mereka?" — trigger sekali saat section masuk viewport
        const testiPath = document.getElementById('testiUnderlinePath');
        if (testiPath) {
            const testiObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        testiPath.classList.add('nz-draw');
                        testiObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.5
            });
            testiObserver.observe(document.getElementById('testimoni'));
        }
    });
</script>
