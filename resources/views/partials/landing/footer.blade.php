{{-- partials/landing/footer.blade.php --}}
@php
    $config = \App\Models\KonfigurasiAplikasi::getConfig();
@endphp

{{-- SEPARATOR: Single smooth curve --}}
<div class="relative overflow-hidden leading-none" style="background-color:transparent; margin-bottom:-2px;">
    <svg viewBox="0 0 1440 60" preserveAspectRatio="none"
         xmlns="http://www.w3.org/2000/svg"
         style="display:block; width:100%; height:60px;">
        <path d="M0,0 C360,60 1080,60 1440,0 L1440,60 L0,60 Z"
              fill="#1a2e1c"/>
    </svg>
</div>

<footer id="kontak" class="relative overflow-hidden" style="background: linear-gradient(160deg, #1a2e1c 0%, #1e3520 50%, #162818 100%);">

    {{-- Background decoration --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <svg class="absolute inset-0 w-full h-full" style="opacity:0.035;">
            <defs>
                <pattern id="footer-dot" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                    <circle cx="1.5" cy="1.5" r="1.5" fill="#7dba6e"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#footer-dot)"/>
        </svg>
        <div class="absolute" style="top:-40px;left:-40px;width:300px;height:300px;background:radial-gradient(circle, rgba(74,163,74,0.1) 0%, transparent 65%);border-radius:50%;"></div>
        <div class="absolute" style="bottom:-60px;right:-30px;width:260px;height:260px;background:radial-gradient(circle, rgba(100,200,80,0.07) 0%, transparent 65%);border-radius:50%;"></div>
        {{-- Floating shapes --}}
        <div class="nz-fb-a absolute rounded-2xl" style="width:55px;height:55px;top:6%;left:1.5%;border:1.5px solid rgba(100,180,80,0.15);background:rgba(60,130,60,0.05);transform:rotate(15deg);"></div>
        <div class="nz-fb-b absolute rounded-xl" style="width:35px;height:35px;top:15%;right:3%;border:1px solid rgba(100,180,80,0.1);background:rgba(60,130,60,0.03);transform:rotate(-10deg);"></div>
        <div class="nz-fb-c absolute rounded-2xl" style="width:62px;height:62px;bottom:12%;left:4%;border:1px solid rgba(100,180,80,0.08);background:rgba(60,130,60,0.025);transform:rotate(6deg);"></div>
    </div>

    {{-- ── KONTEN ── --}}
    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20 pt-8 pb-0">

        {{-- GRID 3 kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-6">

            {{-- ── KOLOM 1: Brand ── --}}
            <div class="flex flex-col items-start nz-footer-reveal">
                <a href="/" class="flex items-center gap-2.5 mb-3 group">
                    @if($config->favicon)
                        <div class="rounded-xl p-1 flex-shrink-0" style="background:rgba(255,255,255,0.08);">
                            <img src="{{ asset('storage/' . $config->favicon) }}"
                                 alt="{{ $config->nama_aplikasi }}"
                                 class="rounded-lg"
                                 style="height:30px; width:auto; object-fit:contain;">
                        </div>
                    @else
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(100,190,80,0.18);">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="color:#a0d870;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex flex-col gap-0.5 leading-none">
                        <span class="font-extrabold text-white text-base tracking-tight">{{ $config->nama_aplikasi }}</span>
                        @if($config->tagline)
                            <span class="text-[9px] font-semibold tracking-[0.2em] uppercase" style="color:rgba(160,216,112,0.65);">{{ $config->tagline }}</span>
                        @endif
                    </div>
                </a>

                <p class="text-xs leading-relaxed mb-4" style="color:rgba(210,235,185,0.5); max-width:20rem;">
                    {{ $config->deskripsi_aplikasi ?? 'Platform digital pengelolaan zakat yang transparan, amanah, dan mudah digunakan untuk masjid dan lembaga amil zakat.' }}
                </p>

                {{-- Badge --}}
                <div class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 mb-4 text-[11px] font-semibold" style="background:rgba(100,190,80,0.1);color:rgba(155,225,105,0.85);">
                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#80d050;"></span>
                    Sistem Aktif & Terpercaya
                </div>

                {{-- Sosial media --}}
                <div class="flex items-center gap-1.5">
                    @if($config->facebook_url)
                    <a href="{{ $config->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"
                       class="nz-social-btn w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.07);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color:rgba(210,235,185,0.65);">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    @if($config->instagram_url)
                    <a href="{{ $config->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram"
                       class="nz-social-btn w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.07);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color:rgba(210,235,185,0.65);">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    @if($config->youtube_url)
                    <a href="{{ $config->youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube"
                       class="nz-social-btn w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.07);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color:rgba(210,235,185,0.65);">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    @endif
                    @if($config->whatsapp_support)
                    <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener" aria-label="WhatsApp"
                       class="nz-social-btn w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-200"
                       style="background:rgba(255,255,255,0.07);">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color:rgba(210,235,185,0.65);">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- ── KOLOM 2: Menu Cepat ── --}}
            <div class="flex flex-col items-start nz-footer-reveal" style="transition-delay:0.1s">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1 h-4 rounded-full" style="background: linear-gradient(180deg,#90d060,#4da830);"></div>
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.18em]" style="color:rgba(210,235,185,0.8);">Menu Cepat</h4>
                </div>
                <div class="w-10 h-px mb-4 ml-3" style="background: linear-gradient(90deg,#6dba5e,transparent);"></div>

                <ul class="space-y-0.5 w-full">
                    @foreach([
                        ['href' => '/',               'label' => 'Beranda',          'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['href' => '',                'label' => 'Hitung Zakat',     'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['href' => '',                'label' => 'Panduan Zakat',    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['href' => '',                'label' => 'Artikel / Buletin','icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                        ['href' => '#kontak',         'label' => 'Kontak',           'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['href' => route('login'),    'label' => 'Masuk',            'icon' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'],
                        ['href' => route('register'), 'label' => 'Daftar Gratis',   'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                    ] as $item)
                    <li>
                        <a href="{{ $item['href'] }}"
                           class="nz-menu-link group flex items-center gap-2.5 py-1.5 px-2.5 rounded-lg text-xs transition-all duration-200"
                           style="color:rgba(200,228,178,0.55);">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-200 nz-menu-icon"
                                 style="background:rgba(90,165,60,0.1);">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="color:rgba(130,200,80,0.65);">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/>
                                </svg>
                            </div>
                            {{ $item['label'] }}
                            <svg class="w-2.5 h-2.5 ml-auto opacity-0 -translate-x-1 transition-all duration-200 nz-menu-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── KOLOM 3: Kontak ── --}}
            <div class="flex flex-col items-start nz-footer-reveal" style="transition-delay:0.2s">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1 h-4 rounded-full" style="background: linear-gradient(180deg,#90d060,#4da830);"></div>
                    <h4 class="text-[11px] font-bold uppercase tracking-[0.18em]" style="color:rgba(210,235,185,0.8);">Kontak Kami</h4>
                </div>
                <div class="w-10 h-px mb-4 ml-3" style="background: linear-gradient(90deg,#6dba5e,transparent);"></div>

                <ul class="space-y-2 w-full">
                    @if($config->email_admin)
                    <li>
                        <a href="mailto:{{ $config->email_admin }}" class="nz-contact-card flex items-center gap-3 p-2.5 rounded-xl transition-all duration-200"
                           style="background:rgba(255,255,255,0.04);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(70,155,50,0.25);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" style="color:#80c860;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:rgba(140,210,90,0.55);">Email</p>
                                <p class="text-xs font-medium" style="color:rgba(210,238,190,0.75);">{{ $config->email_admin }}</p>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if($config->telepon_admin)
                    <li>
                        <a href="tel:{{ $config->telepon_formatted }}" class="nz-contact-card flex items-center gap-3 p-2.5 rounded-xl transition-all duration-200"
                           style="background:rgba(255,255,255,0.04);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(70,155,50,0.25);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" style="color:#80c860;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:rgba(140,210,90,0.55);">Telepon</p>
                                <p class="text-xs font-medium" style="color:rgba(210,238,190,0.75);">{{ $config->telepon_admin }}</p>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if($config->whatsapp_support)
                    <li>
                        <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener" class="nz-contact-card flex items-center gap-3 p-2.5 rounded-xl transition-all duration-200"
                           style="background:rgba(255,255,255,0.04);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(70,155,50,0.25);">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24" style="color:#80c860;">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:rgba(140,210,90,0.55);">WhatsApp</p>
                                <p class="text-xs font-medium" style="color:rgba(210,238,190,0.75);">{{ $config->whatsapp_support }}</p>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if($config->alamat_kantor)
                    <li>
                        <div class="flex items-start gap-3 p-2.5 rounded-xl" style="background:rgba(255,255,255,0.04);">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5" style="background:rgba(70,155,50,0.25);">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" style="color:#80c860;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-widest mb-0.5" style="color:rgba(140,210,90,0.55);">Alamat</p>
                                <p class="text-xs leading-relaxed" style="color:rgba(210,238,190,0.75);">{{ $config->alamat_kantor }}</p>
                            </div>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>

        </div>{{-- /grid --}}

        {{-- Bottom bar — COPYRIGHT DI TENGAH, tanpa border --}}
        <div class="py-4 text-center">
            <p class="text-[11px]" style="color:rgba(190,220,165,0.3);">
                &copy; {{ date('Y') }}
                <span class="font-semibold" style="color:rgba(190,220,165,0.45);">{{ $config->nama_aplikasi }}</span>.
                Seluruh hak cipta dilindungi undang-undang.
            </p>
        </div>

    </div>{{-- /konten --}}

</footer>

<style>
.nz-footer-reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s cubic-bezier(0.4,0,0.2,1), transform 0.6s cubic-bezier(0.4,0,0.2,1);
}
.nz-footer-reveal.nz-footer-visible {
    opacity: 1;
    transform: translateY(0);
}
.nz-social-btn:hover {
    background: rgba(90,175,60,0.2) !important;
    transform: translateY(-2px);
}
.nz-menu-link:hover {
    background: rgba(90,175,60,0.1) !important;
    color: rgba(200,240,170,0.9) !important;
    transform: translateX(3px);
}
.nz-menu-link:hover .nz-menu-icon {
    background: rgba(90,165,60,0.22) !important;
}
.nz-menu-link:hover .nz-menu-arrow {
    opacity: 0.6 !important;
    transform: translateX(0) !important;
}
.nz-contact-card:hover {
    background: rgba(90,175,60,0.09) !important;
    transform: translateX(3px);
}
@keyframes nzFloatA {
    0%,100%{ transform: rotate(15deg) translateY(0); }
    50%{ transform: rotate(15deg) translateY(-8px); }
}
@keyframes nzFloatB {
    0%,100%{ transform: rotate(-10deg) translateY(0); }
    50%{ transform: rotate(-10deg) translateY(6px); }
}
@keyframes nzFloatC {
    0%,100%{ transform: rotate(6deg) translateY(0); }
    50%{ transform: rotate(6deg) translateY(-5px); }
}
.nz-fb-a { animation: nzFloatA 7s ease-in-out infinite; }
.nz-fb-b { animation: nzFloatB 9s ease-in-out infinite; }
.nz-fb-c { animation: nzFloatC 8s ease-in-out infinite; }
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