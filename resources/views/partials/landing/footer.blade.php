{{-- partials/landing/footer.blade.php --}}
@php
    $config = \App\Models\KonfigurasiAplikasi::getConfig();
@endphp

{{-- ============================================================
     WAVE SEPARATOR — melengkung ke bawah masuk footer (warna sama dgn footer)
     Warna fill harus sama persis dengan background footer
     ============================================================ --}}
<div class="relative overflow-hidden leading-none" style="background-color:transparent; margin-bottom:-1px;">
    <svg viewBox="0 0 1440 90" preserveAspectRatio="none"
         xmlns="http://www.w3.org/2000/svg"
         style="display:block; width:100%; height:90px;">
        {{-- Gelombang utama --}}
        <path d="M0,30 C200,90 400,10 720,50 C1040,90 1260,10 1440,45 L1440,90 L0,90 Z"
              fill="#0f1a10"/>
        {{-- Gelombang kedua sedikit offset (layering effect) --}}
        <path d="M0,50 C300,5 600,80 900,30 C1100,0 1300,60 1440,30 L1440,90 L0,90 Z"
              fill="#0d1a11" opacity="0.55"/>
    </svg>
</div>

{{-- ============================================================
     FOOTER UTAMA
     - Background: hijau sangat gelap (bukan hijau terang)
     - Hexagon wireframe subtle
     - Padding: px-4 sm:px-10 lg:px-20 (identik hero & navbar)
     ============================================================ --}}
<footer id="kontak" class="relative overflow-hidden" style="background:#0f1a10;">

    {{-- Dekorasi background --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        {{-- Hexagon wireframe --}}
        <svg class="absolute inset-0 w-full h-full opacity-[0.055]">
            <defs>
                <pattern id="footer-hex-pat" x="0" y="0" width="60" height="52" patternUnits="userSpaceOnUse">
                    <polygon points="30,2 58,17 58,47 30,62 2,47 2,17" fill="none" stroke="#aed581" stroke-width="1.2"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#footer-hex-pat)"/>
        </svg>
        {{-- Floating boxes —animasi dari content.blade.php --}}
        <div class="nz-fb-a absolute rounded-2xl border-2" style="width:68px;height:68px;top:8%;left:1.5%;border-color:rgba(45,105,54,0.2);background:rgba(45,105,54,0.04);transform:rotate(12deg);"></div>
        <div class="nz-fb-b absolute rounded-xl border"  style="width:42px;height:42px;top:12%;right:2%;border-color:rgba(45,105,54,0.14);background:rgba(45,105,54,0.03);transform:rotate(-8deg);"></div>
        <div class="nz-fb-c absolute rounded-2xl border" style="width:76px;height:76px;bottom:10%;left:4%;border-color:rgba(45,105,54,0.12);background:rgba(45,105,54,0.025);transform:rotate(5deg);"></div>
        {{-- Radial glow aksen --}}
        <div class="absolute -top-20 -left-20 w-64 h-64 rounded-full" style="background:radial-gradient(circle, rgba(45,105,54,0.18) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-16 -right-16 w-52 h-52 rounded-full" style="background:radial-gradient(circle, rgba(124,179,66,0.1) 0%, transparent 70%);"></div>
    </div>

    {{-- ── KONTEN FOOTER ── --}}
    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20 pt-10 pb-0">

        {{-- GRID: 3 kolom — Brand | Menu Cepat | Kontak --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b" style="border-color:rgba(255,255,255,0.08);">

            {{-- ── KOLOM 1: Brand ── --}}
            <div class="flex flex-col items-start nz-footer-reveal">

                {{-- Logo: favicon asli + nama aplikasi --}}
                <a href="/" class="flex items-center gap-3 mb-4 group">
                    @if($config->favicon)
                        {{-- Favicon asli ditampilkan apa adanya, tanpa kotak, tanpa filter --}}
                        <img
                            src="{{ asset('storage/' . $config->favicon) }}"
                            alt="{{ $config->nama_aplikasi }}"
                            class="flex-shrink-0 rounded-lg"
                            style="height:40px; width:auto; object-fit:contain;"
                        >
                    @else
                        {{-- Fallback SVG --}}
                        <svg class="w-10 h-10 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:rgba(174,213,129,0.9);">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                    <div class="flex flex-col leading-none gap-1">
                        <span class="font-bold text-white text-base tracking-tight leading-none">{{ $config->nama_aplikasi }}</span>
                        @if($config->tagline)
                            <span class="text-white/40 text-[10px] font-medium tracking-widest uppercase leading-none mt-1">{{ $config->tagline }}</span>
                        @endif
                    </div>
                </a>

                <p class="text-white/50 text-sm leading-relaxed mb-5" style="max-width:21rem;">
                    {{ $config->deskripsi_aplikasi ?? 'Platform digital pengelolaan zakat yang transparan, amanah, dan mudah digunakan untuk masjid dan lembaga amil zakat.' }}
                </p>

                {{-- Sosial media dari model --}}
                <div class="flex items-center gap-2">
                    @if($config->facebook_url)
                    <a href="{{ $config->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);"
                       onmouseover="this.style.background='rgba(45,105,54,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <svg class="w-3.5 h-3.5 text-white/70" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    @if($config->instagram_url)
                    <a href="{{ $config->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);"
                       onmouseover="this.style.background='rgba(45,105,54,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <svg class="w-3.5 h-3.5 text-white/70" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    @if($config->youtube_url)
                    <a href="{{ $config->youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);"
                       onmouseover="this.style.background='rgba(45,105,54,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <svg class="w-3.5 h-3.5 text-white/70" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    @endif
                    @if($config->whatsapp_support)
                    <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener" aria-label="WhatsApp"
                       class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);"
                       onmouseover="this.style.background='rgba(45,105,54,0.5)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
                        <svg class="w-3.5 h-3.5 text-white/70" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                    </a>
                    @endif
                    @if(!$config->facebook_url && !$config->instagram_url && !$config->youtube_url && !$config->whatsapp_support)
                        <span class="text-white/25 text-xs italic">—</span>
                    @endif
                </div>

            </div>

            {{-- ── KOLOM 2: Menu Cepat ── --}}
            <div class="flex flex-col items-start nz-footer-reveal" style="transition-delay:0.1s">
                <h4 class="text-white/90 font-bold text-xs mb-1 uppercase tracking-widest">Menu Cepat</h4>
                {{-- Underline aksen hijau --}}
                <div class="w-8 h-0.5 mb-5 rounded-full" style="background:#aed581;"></div>
                <ul class="space-y-3">
                    @foreach([
                        ['href' => '/',               'label' => 'Beranda'],
                        ['href' => '',                'label' => 'Hitung Zakat'],
                        ['href' => '',                'label' => 'Panduan Zakat'],
                        ['href' => '',                'label' => 'Artikel Terkini / Buletin'],
                        ['href' => '#kontak',         'label' => 'Kontak'],
                        ['href' => route('login'),    'label' => 'Masuk'],
                        ['href' => route('register'), 'label' => 'Daftar Gratis'],
                    ] as $item)
                    <li>
                        <a href="{{ $item['href'] }}"
                           class="group inline-flex items-center gap-2 text-sm transition-colors duration-200"
                           style="color:rgba(255,255,255,0.55);"
                           onmouseover="this.style.color='rgba(255,255,255,1)'"
                           onmouseout="this.style.color='rgba(255,255,255,0.55)'">
                            <span class="w-1 h-1 rounded-full flex-shrink-0 transition-all duration-200 group-hover:scale-150"
                                  style="background:rgba(174,213,129,0.6);"></span>
                            {{ $item['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── KOLOM 3: Kontak ── --}}
            <div class="flex flex-col items-start nz-footer-reveal" style="transition-delay:0.2s">
                <h4 class="text-white/90 font-bold text-xs mb-1 uppercase tracking-widest">Kontak Kami</h4>
                {{-- Underline aksen hijau --}}
                <div class="w-8 h-0.5 mb-5 rounded-full" style="background:#aed581;"></div>
                <ul class="space-y-4">

                    {{-- Email admin --}}
                    @if($config->email_admin)
                    <li>
                        <a href="mailto:{{ $config->email_admin }}" class="flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200"
                                 style="background:rgba(45,105,54,0.4);border:1px solid rgba(174,213,129,0.2);"
                                 onmouseover="this.style.background='rgba(45,105,54,0.7)'" onmouseout="this.style.background='rgba(45,105,54,0.4)'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="color:#aed581;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/35 text-[11px] mb-0.5">Email</p>
                                <p class="text-white/65 text-sm group-hover:text-white transition-colors duration-200">{{ $config->email_admin }}</p>
                            </div>
                        </a>
                    </li>
                    @endif

                    {{-- Telepon admin --}}
                    @if($config->telepon_admin)
                    <li>
                        <a href="tel:{{ $config->telepon_formatted }}" class="flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200"
                                 style="background:rgba(45,105,54,0.4);border:1px solid rgba(174,213,129,0.2);"
                                 onmouseover="this.style.background='rgba(45,105,54,0.7)'" onmouseout="this.style.background='rgba(45,105,54,0.4)'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="color:#aed581;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/35 text-[11px] mb-0.5">Telepon</p>
                                <p class="text-white/65 text-sm group-hover:text-white transition-colors duration-200">{{ $config->telepon_admin }}</p>
                            </div>
                        </a>
                    </li>
                    @endif

                    {{-- WhatsApp --}}
                    @if($config->whatsapp_support)
                    <li>
                        <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener" class="flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200"
                                 style="background:rgba(45,105,54,0.4);border:1px solid rgba(174,213,129,0.2);"
                                 onmouseover="this.style.background='rgba(45,105,54,0.7)'" onmouseout="this.style.background='rgba(45,105,54,0.4)'">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color:#aed581;">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white/35 text-[11px] mb-0.5">WhatsApp</p>
                                <p class="text-white/65 text-sm group-hover:text-white transition-colors duration-200">{{ $config->whatsapp_support }}</p>
                            </div>
                        </a>
                    </li>
                    @endif

                    {{-- Alamat kantor --}}
                    @if($config->alamat_kantor)
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                             style="background:rgba(45,105,54,0.4);border:1px solid rgba(174,213,129,0.2);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="color:#aed581;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white/35 text-[11px] mb-0.5">Alamat</p>
                            <p class="text-white/65 text-sm leading-relaxed">{{ $config->alamat_kantor }}</p>
                        </div>
                    </li>
                    @endif

                </ul>
            </div>

        </div>{{-- /grid --}}

        {{-- Bottom bar --}}
        <div class="py-5 text-center">
            <p class="text-white/25 text-xs">
                &copy; {{ date('Y') }}
                <span class="font-semibold text-white/40">{{ $config->nama_aplikasi }}</span>.
                Seluruh hak cipta dilindungi undang-undang.
            </p>
        </div>

    </div>{{-- /konten --}}

</footer>

<style>
.nz-footer-reveal {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.65s cubic-bezier(0.4,0,0.2,1), transform 0.65s cubic-bezier(0.4,0,0.2,1);
}
.nz-footer-reveal.nz-footer-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>

<script>
(function () {
    function showAll() {
        document.querySelectorAll('.nz-footer-reveal').forEach(function(el) {
            el.classList.add('nz-footer-visible');
        });
    }
    if (!('IntersectionObserver' in window)) { showAll(); return; }

    var obs = new IntersectionObserver(function(entries, ob) {
        entries.forEach(function(e) {
            if (e.isIntersecting) { e.target.classList.add('nz-footer-visible'); ob.unobserve(e.target); }
        });
    }, { threshold: 0.05 });

    document.querySelectorAll('.nz-footer-reveal').forEach(function(el) { obs.observe(el); });

    setTimeout(function() {
        document.querySelectorAll('.nz-footer-reveal').forEach(function(el) {
            if (el.getBoundingClientRect().top < window.innerHeight) el.classList.add('nz-footer-visible');
        });
    }, 150);
})();
</script>