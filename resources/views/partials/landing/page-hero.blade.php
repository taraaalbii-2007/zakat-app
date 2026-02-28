{{-- resources/views/partials/landing/page-hero.blade.php --}}
{{--
    ╔══════════════════════════════════════════════════════════════════╗
    ║  REUSABLE PAGE HERO                                              ║
    ║                                                                  ║
    ║  Props:                                                          ║
    ║  - breadcrumb    (string)  : teks label breadcrumb — wajib       ║
    ║  - badge         (string)  : teks badge hijau kecil — opsional   ║
    ║  - heroTitle     (string)  : judul utama                         ║
    ║  - heroHighlight (string)  : bagian judul berwarna hijau         ║
    ║  - heroSubtitle  (string)  : deskripsi di bawah judul            ║
    ║  - infoStrip     (array)   : item info di sebelah kanan          ║
    ║      tiap item: [                                                ║
    ║        'label'     => string,   // teks label kecil              ║
    ║        'value'     => string,   // nilai utama                   ║
    ║        'unit'      => string,   // satuan kecil (/gram) opsional ║
    ║        'sub'       => string,   // teks kecil bawah    opsional  ║
    ║        'highlight' => bool,     // warna hijau primary opsional  ║
    ║      ]                                                           ║
    ╚══════════════════════════════════════════════════════════════════╝
--}}

@php
    $breadcrumb    = $breadcrumb    ?? 'Halaman';
    $badge         = $badge         ?? null;
    $heroTitle     = $heroTitle     ?? 'Judul Halaman';
    $heroHighlight = $heroHighlight ?? null;
    $heroSubtitle  = $heroSubtitle  ?? null;
    $infoStrip     = $infoStrip     ?? [];
    $hasInfoStrip  = count($infoStrip) > 0;
@endphp

<section class="relative bg-white pt-28 pb-12 overflow-hidden">

    {{-- ── Decorative Background ────────────────────────────── --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 left-0 w-full h-0.5
            bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600"></div>
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full opacity-[0.04]"
            style="background: radial-gradient(circle, #16a34a, transparent 70%)"></div>
        <div class="absolute bottom-0 left-0 w-full h-px bg-neutral-100"></div>
        <div class="absolute inset-0 opacity-[0.015]"
            style="background-image: radial-gradient(circle, #16a34a 1px, transparent 1px);
                   background-size: 24px 24px;"></div>
    </div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ── Breadcrumb ────────────────────────────────────── --}}
        <nav class="flex items-center gap-2 text-xs text-neutral-400 mb-8" aria-label="Breadcrumb">
            <a href="{{ route('landing') }}"
               class="hover:text-primary-600 transition-colors duration-200">Beranda</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-neutral-700 font-semibold">{{ $breadcrumb }}</span>
        </nav>

        {{-- ── Hero Layout ────────────────────────────────────── --}}
        <div @class([
            'flex flex-col gap-8',
            'lg:flex-row lg:items-end lg:justify-between' => $hasInfoStrip,
        ])>

            {{-- Left / Main: Title & Description ───────────── --}}
            <div @class(['max-w-xl' => $hasInfoStrip, 'max-w-2xl' => !$hasInfoStrip])>

                @if($badge)
                    <span class="inline-flex items-center gap-2 bg-primary-50 text-primary-600 text-xs
                        font-semibold tracking-widest uppercase px-4 py-1.5 rounded-full
                        border border-primary-100 mb-5">
                        {{ $badge }}
                    </span>
                @endif

                <h1 class="text-3xl sm:text-4xl font-bold text-neutral-900 leading-tight">
                    {{ $heroTitle }}
                    @if($heroHighlight)
                        <br><span class="text-primary-600">{{ $heroHighlight }}</span>
                    @endif
                </h1>

                @if($heroSubtitle)
                    <p class="mt-4 text-neutral-500 text-sm leading-relaxed">
                        {{ $heroSubtitle }}
                    </p>
                @endif

            </div>

            {{-- Right: Info Strip (opsional) ───────────────── --}}
            @if($hasInfoStrip)
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 bg-neutral-50
                    border border-neutral-200 rounded-2xl px-6 py-4 text-sm flex-shrink-0">

                    @foreach($infoStrip as $i => $item)
                        @if($i > 0)
                            <div class="w-px h-10 bg-neutral-200 hidden sm:block" aria-hidden="true"></div>
                        @endif
                        <div>
                            <p class="text-xs text-neutral-400 font-medium uppercase tracking-wide mb-0.5">
                                {{ $item['label'] }}
                            </p>
                            <p @class([
                                'font-bold',
                                'text-primary-600' => $item['highlight'] ?? false,
                                'text-neutral-800' => !($item['highlight'] ?? false),
                            ])>
                                {{ $item['value'] }}
                                @if($item['unit'] ?? null)
                                    <span class="text-neutral-400 font-normal text-xs">{{ $item['unit'] }}</span>
                                @endif
                            </p>
                            @if($item['sub'] ?? null)
                                <p class="text-xs text-neutral-400 mt-0.5">{{ $item['sub'] }}</p>
                            @endif
                        </div>
                    @endforeach

                </div>
            @endif

        </div>
    </div>
</section>