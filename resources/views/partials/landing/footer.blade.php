{{-- partials/landing/footer.blade.php --}}
@php
    $config = \App\Models\KonfigurasiAplikasi::getConfig();
@endphp

{{-- SEPARATOR: Smooth single arc — warna harus sama persis dengan footer --}}
<div class="relative overflow-hidden leading-none" style="background-color:transparent; margin-bottom:-2px;">
    <svg viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"
        style="display:block; width:100%; height:70px;">
        {{-- Layer 1: lengkungan utama --}}
        <path d="M0,0 C480,70 960,70 1440,0 L1440,70 L0,70 Z" fill="#16a34a" />
        {{-- Layer 2: sedikit shadow depth --}}
        <path d="M0,0 C480,70 960,70 1440,0 L1440,70 L0,70 Z" fill="#15803d" opacity="0.3" />
    </svg>
</div>

<footer id="kontak" class="relative overflow-hidden"
    style="background: linear-gradient(175deg, #16a34a 0%, #15803d 60%, #166534 100%);">

    {{-- BACKGROUND: Dot grid titik-titik --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">

        {{-- Dot grid utama --}}
        <svg class="absolute inset-0 w-full h-full" style="opacity:0.18;">
            <defs>
                <pattern id="nz-dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="#bbf7d0" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#nz-dots)" />
        </svg>

        {{-- Dot grid besar offset (lebih subtle) --}}
        <svg class="absolute inset-0 w-full h-full" style="opacity:0.08;">
            <defs>
                <pattern id="nz-dots-lg" x="10" y="10" width="40" height="40" patternUnits="userSpaceOnUse">
                    <circle cx="3" cy="3" r="2.5" fill="#dcfce7" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#nz-dots-lg)" />
        </svg>

        {{-- Floating persegi panjang animasi --}}
        <div class="nz-fb-a absolute"
            style="width:90px;height:48px;top:8%;left:2%;border:2px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.04);border-radius:6px;transform:rotate(8deg);">
        </div>
        <div class="nz-fb-b absolute"
            style="width:56px;height:56px;top:18%;right:4%;border:2px solid rgba(255,255,255,0.15);background:rgba(255,255,255,0.03);border-radius:4px;transform:rotate(-6deg);">
        </div>
        <div class="nz-fb-c absolute"
            style="width:72px;height:36px;bottom:18%;left:5%;border:1.5px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.025);border-radius:5px;transform:rotate(4deg);">
        </div>
        <div class="nz-fb-d absolute"
            style="width:44px;height:76px;bottom:12%;right:6%;border:1.5px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.025);border-radius:4px;transform:rotate(-3deg);">
        </div>

        {{-- Radial glow --}}
        <div class="absolute"
            style="top:-60px;left:-60px;width:340px;height:340px;background:radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 65%);border-radius:50%;">
        </div>
        <div class="absolute"
            style="bottom:-40px;right:-40px;width:280px;height:280px;background:radial-gradient(circle, rgba(255,255,255,0.07) 0%, transparent 65%);border-radius:50%;">
        </div>
    </div>

    {{-- KONTEN --}}
    <div class="relative z-10 w-full px-4 sm:px-10 lg:px-20 pt-10 pb-0">

        {{-- GRID 3 kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-8">

            {{-- KOLOM 1: Brand --}}
            <div class="flex flex-col items-start nz-footer-reveal">

                <a href="/" class="flex items-center gap-3 mb-4 group">
                    <div class="rounded-xl p-1.5 flex-shrink-0" style="background:rgba(255,255,255,0.2);">
                        <img src="{{ asset('images/logo_zakat.png') }}" alt="{{ $config->nama_aplikasi }}"
                            class="rounded-lg" style="height:32px;width:auto;object-fit:contain;">
                    </div>
                    <div class="flex flex-col gap-0.5 leading-none">
                        <span
                            class="font-extrabold text-white text-base tracking-tight">{{ $config->nama_aplikasi }}</span>
                        @if ($config->tagline)
                            <span class="text-[9px] font-bold tracking-[0.22em] uppercase"
                                style="color:rgba(220,252,231,0.75);">{{ $config->tagline }}</span>
                        @endif
                    </div>
                </a>

                <p class="text-xs leading-relaxed mb-5" style="color:rgba(220,252,231,0.8);max-width:18rem;">
                    {{ $config->deskripsi_aplikasi ?? 'Platform digital pengelolaan zakat yang transparan, amanah, dan mudah digunakan untuk masjid dan lembaga amil zakat.' }}
                </p>

                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 mb-5 text-[11px] font-bold"
                    style="background:rgba(255,255,255,0.15);color:#ffffff;">
                    <span class="w-1.5 h-1.5 rounded-sm animate-pulse" style="background:#ffffff;"></span>
                    Sistem Aktif & Terpercaya
                </div>

                {{-- Sosial media --}}
                <div class="flex items-center gap-2">
                    @if ($config->facebook_url)
                        <a href="{{ $config->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"
                            class="nz-social-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                            style="background:rgba(255,255,255,0.15);">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                    @endif
                    @if ($config->instagram_url)
                        <a href="{{ $config->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram"
                            class="nz-social-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                            style="background:rgba(255,255,255,0.15);">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    @endif
                    @if ($config->youtube_url)
                        <a href="{{ $config->youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube"
                            class="nz-social-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                            style="background:rgba(255,255,255,0.15);">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    @endif
                    @if ($config->whatsapp_support)
                        <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener" aria-label="WhatsApp"
                            class="nz-social-btn w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200"
                            style="background:rgba(255,255,255,0.15);">
                            <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- KOLOM 2: Menu Cepat --}}
            <div class="flex flex-col items-start nz-footer-reveal" style="transition-delay:0.1s">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 rounded-sm" style="background:rgba(255,255,255,0.6);"></div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white">Menu Cepat</h4>
                </div>
                <ul class="space-y-0.5 w-full">
                    @foreach ([['href' => '/', 'label' => 'Beranda'], ['href' => '', 'label' => 'Hitung Zakat'], ['href' => '', 'label' => 'Panduan Zakat'], ['href' => '', 'label' => 'Artikel / Buletin'], ['href' => '#kontak', 'label' => 'Kontak'], ['href' => route('login'), 'label' => 'Masuk'], ['href' => route('register'), 'label' => 'Daftar Gratis']] as $item)
                        <li>
                            <a href="{{ $item['href'] }}"
                                class="nz-menu-link group flex items-center gap-2.5 py-1.5 px-2 rounded-lg text-xs font-medium transition-all duration-150"
                                style="color:rgba(220,252,231,0.8);">
                                <span class="w-1 h-1 rounded-full flex-shrink-0 transition-all duration-150"
                                    style="background:rgba(255,255,255,0.5);"></span>
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- KOLOM 3: Kontak --}}
            <div class="flex flex-col items-start nz-footer-reveal" style="transition-delay:0.2s">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-5 rounded-sm" style="background:rgba(255,255,255,0.6);"></div>
                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white">Kontak Kami</h4>
                </div>
                <ul class="space-y-2 w-full">
                    @if ($config->email_admin)
                        <li>
                            <a href="mailto:{{ $config->email_admin }}"
                                class="nz-contact-item flex items-center gap-3 py-2 px-3 rounded-xl transition-all duration-150"
                                style="background:rgba(255,255,255,0.12);">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background:rgba(255,255,255,0.2);">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-black uppercase tracking-widest"
                                        style="color:rgba(220,252,231,0.7);">Email</p>
                                    <p class="text-xs font-medium text-white truncate">{{ $config->email_admin }}</p>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if ($config->telepon_admin)
                        <li>
                            <a href="tel:{{ $config->telepon_formatted }}"
                                class="nz-contact-item flex items-center gap-3 py-2 px-3 rounded-xl transition-all duration-150"
                                style="background:rgba(255,255,255,0.12);">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background:rgba(255,255,255,0.2);">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-black uppercase tracking-widest"
                                        style="color:rgba(220,252,231,0.7);">Telepon</p>
                                    <p class="text-xs font-medium text-white">{{ $config->telepon_admin }}</p>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if ($config->whatsapp_support)
                        <li>
                            <a href="{{ $config->whatsapp_link }}" target="_blank" rel="noopener"
                                class="nz-contact-item flex items-center gap-3 py-2 px-3 rounded-xl transition-all duration-150"
                                style="background:rgba(255,255,255,0.12);">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background:rgba(255,255,255,0.2);">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[9px] font-black uppercase tracking-widest"
                                        style="color:rgba(220,252,231,0.7);">WhatsApp</p>
                                    <p class="text-xs font-medium text-white">{{ $config->whatsapp_support }}</p>
                                </div>
                            </a>
                        </li>
                    @endif
                    @if ($config->alamat_kantor)
                        <li>
                            <div class="flex items-start gap-3 py-2 px-3 rounded-xl"
                                style="background:rgba(255,255,255,0.12);">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                    style="background:rgba(255,255,255,0.2);">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest"
                                        style="color:rgba(220,252,231,0.7);">Alamat</p>
                                    <p class="text-xs font-medium text-white leading-relaxed mt-0.5">
                                        {{ $config->alamat_kantor }}</p>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

        </div>{{-- /grid --}}

        {{-- Divider --}}
        <div class="w-full h-px" style="background:rgba(255,255,255,0.2);"></div>

        {{-- Copyright tengah --}}
        <div class="py-4 text-center">
            <p class="text-[11px]" style="color:rgba(220,252,231,0.55);">
                &copy; {{ date('Y') }}
                <span class="font-bold" style="color:rgba(220,252,231,0.8);">{{ $config->nama_aplikasi }}</span>.
                Seluruh hak cipta dilindungi undang-undang.
            </p>
        </div>

    </div>{{-- /konten --}}

</footer>

<style>
    .nz-footer-reveal {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity 0.55s cubic-bezier(0.4, 0, 0.2, 1), transform 0.55s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .nz-footer-reveal.nz-footer-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .nz-social-btn:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        transform: translateY(-2px);
    }

    .nz-menu-link:hover {
        background: rgba(255, 255, 255, 0.12) !important;
        color: #ffffff !important;
        padding-left: 14px !important;
    }

    .nz-menu-link:hover span {
        background: #ffffff !important;
        transform: scale(1.5);
    }

    .nz-contact-item:hover {
        background: rgba(255, 255, 255, 0.2) !important;
        transform: translateX(2px);
    }

    @keyframes nzFloatA {

        0%,
        100% {
            transform: rotate(8deg) translateY(0);
        }

        50% {
            transform: rotate(8deg) translateY(-7px);
        }
    }

    @keyframes nzFloatB {

        0%,
        100% {
            transform: rotate(-6deg) translateY(0);
        }

        50% {
            transform: rotate(-6deg) translateY(6px);
        }
    }

    @keyframes nzFloatC {

        0%,
        100% {
            transform: rotate(4deg) translateY(0);
        }

        50% {
            transform: rotate(4deg) translateY(-5px);
        }
    }

    @keyframes nzFloatD {

        0%,
        100% {
            transform: rotate(-3deg) translateY(0);
        }

        50% {
            transform: rotate(-3deg) translateY(5px);
        }
    }

    .nz-fb-a {
        animation: nzFloatA 7s ease-in-out infinite;
    }

    .nz-fb-b {
        animation: nzFloatB 9s ease-in-out infinite;
    }

    .nz-fb-c {
        animation: nzFloatC 8s ease-in-out infinite;
    }

    .nz-fb-d {
        animation: nzFloatD 10s ease-in-out infinite;
    }
</style>

<script>
    (function() {
        function showAll() {
            document.querySelectorAll('.nz-footer-reveal').forEach(function(el) {
                el.classList.add('nz-footer-visible');
            });
        }
        if (!('IntersectionObserver' in window)) {
            showAll();
            return;
        }
        var obs = new IntersectionObserver(function(entries, ob) {
            entries.forEach(function(e) {
                if (e.isIntersecting) {
                    e.target.classList.add('nz-footer-visible');
                    ob.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.05
        });
        document.querySelectorAll('.nz-footer-reveal').forEach(function(el) {
            obs.observe(el);
        });
        setTimeout(function() {
            document.querySelectorAll('.nz-footer-reveal').forEach(function(el) {
                if (el.getBoundingClientRect().top < window.innerHeight) el.classList.add(
                    'nz-footer-visible');
            });
        }, 150);
    })();
</script>
