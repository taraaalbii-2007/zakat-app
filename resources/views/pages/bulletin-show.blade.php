{{-- resources/views/pages/bulletin-show.blade.php --}}

@extends('layouts.guest')

@section('title', $bulletin->judul)

@section('content')

{{-- PAGE HERO --}}
@include('partials.landing.page-hero', [
    'heroTitle'    => \Illuminate\Support\Str::limit($bulletin->judul, 55),
    'heroSubtitle' => $bulletin->kategoriBulletin->nama_kategori ?? 'Bulletin & Artikel',
])

@php
    $tgl = $bulletin->published_at ?? $bulletin->created_at;
    $hariInd = [
        'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
    ];
    $carbonTgl    = \Carbon\Carbon::parse($tgl);
    $hariId       = $hariInd[$carbonTgl->format('l')] ?? $carbonTgl->format('l');
    $tanggalFmt   = $hariId . ', ' . $carbonTgl->isoFormat('D MMMM YYYY, HH:mm') . ' WIB';
    $authorNama   = $bulletin->author->nama ?? $bulletin->author->name ?? 'Admin';
    $authorFoto   = (isset($bulletin->author) && $bulletin->author && !empty($bulletin->author->foto))
                        ? asset('storage/' . $bulletin->author->foto) : null;
    $kategoriNama = $bulletin->kategoriBulletin->nama_kategori ?? null;
    $kategoriId   = $bulletin->kategoriBulletin->id ?? null;
    $shareUrl     = url()->current();
    $shareText    = urlencode($bulletin->judul . ' - ' . $shareUrl);
@endphp

<div class="bg-white" style="padding-top: 0.75rem;">
    <div class="container mx-auto px-4 sm:px-10 lg:px-20 py-6 pb-8 sm:pb-12 lg:pb-16">
        <div class="max-w-7xl mx-auto">

            {{-- ════════════════════════════════════════
                 DESKTOP: tabel 70% + 30%
            ════════════════════════════════════════ --}}
            <div class="hidden lg:block">
                <table class="w-full" style="border-collapse: collapse;">
                    <tr>
                        {{-- KOLOM KIRI: artikel --}}
                        <td style="width: 70%; vertical-align: top; padding-right: 2rem;">
                            <article>

                                {{-- Breadcrumb --}}
                                <div class="mb-3 flex items-center text-sm text-gray-600 space-x-2">
                                    <a href="{{ route('artikel.index') }}" class="hover:underline">Bulletin</a>
                                    @if($kategoriNama && $kategoriId)
                                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <a href="{{ route('artikel.index', ['kategori' => $kategoriId]) }}"
                                           class="text-green-600 font-semibold hover:underline">
                                            {{ $kategoriNama }}
                                        </a>
                                    @endif
                                </div>

                                {{-- Judul --}}
                                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 leading-tight">
                                    {{ $bulletin->judul }}
                                </h1>

                                {{-- Org + Tanggal + Author --}}
                                <div class="py-4">
                                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                                        <span class="text-green-600 font-semibold">
                                            {{ config('app.name', 'ZakatApp') }}
                                        </span>
                                        <span class="text-gray-400">-</span>
                                        <span>{{ $tanggalFmt }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                                            @if($authorFoto)
                                                <img src="{{ $authorFoto }}" alt="{{ $authorNama }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col text-sm">
                                            <span class="font-semibold text-gray-900">{{ $authorNama }}</span>
                                            <span class="text-gray-500">Penulis</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Share --}}
                                <div class="mb-6 pb-6 border-b border-gray-200">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-600">Bagikan:</span>

                                        {{-- WhatsApp --}}
                                        <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener noreferrer"
                                           class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-green-500 hover:bg-green-50 transition-all duration-300">
                                            <svg class="w-4 h-4 text-gray-600 group-hover:text-green-600 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                        </a>

                                        {{-- Facebook --}}
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                                           class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-blue-600 hover:bg-blue-50 transition-all duration-300">
                                            <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-600 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>

                                        {{-- X / Twitter --}}
                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($bulletin->judul) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                                           class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-gray-900 hover:bg-gray-100 transition-all duration-300">
                                            <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-900 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.742l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                        </a>

                                        {{-- Copy link --}}
                                        <button onclick="bsCopyLink(event, '{{ $shareUrl }}')"
                                            class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-purple-500 hover:bg-purple-50 transition-all duration-300"
                                            title="Salin Link">
                                            <svg class="w-4 h-4 text-gray-600 group-hover:text-purple-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Thumbnail Utama --}}
                                @if($bulletin->thumbnail)
                                    <div class="w-full mb-6 overflow-hidden rounded-lg bg-white flex justify-center items-center">
                                        <div class="relative w-full" style="aspect-ratio: 16/8; background-color: white;">
                                            <img src="{{ asset('storage/' . $bulletin->thumbnail) }}"
                                                 alt="{{ $bulletin->judul }}"
                                                 class="w-full h-full object-cover"
                                                 loading="lazy">
                                            @if($bulletin->image_caption)
                                                <div class="absolute bottom-0 left-0 w-full bg-black/50 text-white text-sm sm:text-base px-4 py-2 sm:py-3 backdrop-blur-sm">
                                                    <p class="text-justify italic leading-snug">{{ $bulletin->image_caption }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Konten Artikel --}}
                                <div class="prose prose-lg max-w-none
                                    prose-headings:font-bold prose-headings:text-gray-900
                                    prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                                    prose-a:text-green-600 prose-a:no-underline hover:prose-a:underline
                                    prose-strong:text-gray-900 prose-strong:font-bold
                                    prose-ul:text-gray-700 prose-ol:text-gray-700
                                    prose-img:rounded-lg prose-img:my-6
                                    prose-blockquote:border-l-4 prose-blockquote:border-gray-300 prose-blockquote:pl-4 prose-blockquote:italic prose-blockquote:text-gray-600">
                                    <p><strong>{{ $bulletin->lokasi ? $bulletin->lokasi . ', ' : 'Jakarta, Indonesia, ' }}{{ config('app.name') }} -- </strong>{!! $bulletin->konten !!}</p>
                                </div>
                            </article>
                        </td>

                        {{-- KOLOM KANAN: sidebar --}}
                        <td style="width: 30%; vertical-align: top; padding-left: 2rem;">
                            <div style="position: sticky; top: 6rem;">
                                @include('partials.landing.bulletin-sidebar', ['related' => $related])
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ════════════════════════════════════════
                 MOBILE: artikel dulu, lalu sidebar bawah
            ════════════════════════════════════════ --}}
            <div class="block lg:hidden">
                <article>

                    {{-- Breadcrumb --}}
                    <div class="mb-3 flex items-center text-sm text-gray-600 space-x-2">
                        <a href="{{ route('artikel.index') }}" class="hover:underline">Bulletin</a>
                        @if($kategoriNama && $kategoriId)
                            <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <a href="{{ route('artikel.index', ['kategori' => $kategoriId]) }}"
                               class="text-green-600 font-semibold hover:underline">
                                {{ $kategoriNama }}
                            </a>
                        @endif
                    </div>

                    {{-- Judul --}}
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2 leading-tight">
                        {{ $bulletin->judul }}
                    </h1>

                    {{-- Org + Tanggal + Author --}}
                    <div class="py-4">
                        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                            <span class="text-green-600 font-semibold">{{ config('app.name', 'ZakatApp') }}</span>
                            <span class="text-gray-400">-</span>
                            <span>{{ $tanggalFmt }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($authorFoto)
                                    <img src="{{ $authorFoto }}" alt="{{ $authorNama }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-green-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex flex-col text-sm">
                                <span class="font-semibold text-gray-900">{{ $authorNama }}</span>
                                <span class="text-gray-500">Penulis</span>
                            </div>
                        </div>
                    </div>

                    {{-- Share --}}
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-600">Bagikan:</span>

                            <a href="https://wa.me/?text={{ $shareText }}" target="_blank" rel="noopener noreferrer"
                               class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-green-500 hover:bg-green-50 transition-all duration-300">
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-green-600 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </a>

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                               class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-blue-600 hover:bg-blue-50 transition-all duration-300">
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-600 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>

                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($bulletin->judul) }}&url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener noreferrer"
                               class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-gray-900 hover:bg-gray-100 transition-all duration-300">
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-gray-900 transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.742l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.911-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>

                            <button onclick="bsCopyLink(event, '{{ $shareUrl }}')"
                                class="group w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-full hover:border-purple-500 hover:bg-purple-50 transition-all duration-300"
                                title="Salin Link">
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-purple-600 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    @if($bulletin->thumbnail)
                        <div class="w-full mb-6 overflow-hidden rounded-lg bg-white flex justify-center items-center">
                            <div class="relative w-full" style="aspect-ratio: 16/8; background-color: white;">
                                <img src="{{ asset('storage/' . $bulletin->thumbnail) }}"
                                     alt="{{ $bulletin->judul }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                                @if($bulletin->image_caption)
                                    <div class="absolute bottom-0 left-0 w-full bg-black/50 text-white text-sm sm:text-base px-4 py-2 sm:py-3 backdrop-blur-sm">
                                        <p class="text-justify italic leading-snug">{{ $bulletin->image_caption }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Konten --}}
                    <div class="prose prose-lg max-w-none
                        prose-headings:font-bold prose-headings:text-gray-900
                        prose-p:text-gray-700 prose-p:leading-relaxed prose-p:mb-4
                        prose-a:text-green-600 prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-gray-900 prose-strong:font-bold
                        prose-ul:text-gray-700 prose-ol:text-gray-700
                        prose-img:rounded-lg prose-img:my-6
                        prose-blockquote:border-l-4 prose-blockquote:border-gray-300 prose-blockquote:pl-4 prose-blockquote:italic prose-blockquote:text-gray-600">
                        <p><strong>{{ $bulletin->lokasi ? $bulletin->lokasi . ', ' : 'Jakarta, Indonesia, ' }}{{ config('app.name') }} -- </strong>{!! $bulletin->konten !!}</p>
                    </div>

                    {{-- Lokasi --}}
                    @if($bulletin->lokasi)
                        <div class="mt-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 border border-green-200 rounded-full text-sm text-green-700 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $bulletin->lokasi }}
                            </span>
                        </div>
                    @endif

                </article>

                {{-- Sidebar di bawah untuk mobile --}}
                <div class="mt-8 mb-8">
                    @include('partials.landing.bulletin-sidebar', ['related' => $related])
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function bsCopyLink(e, url) {
    var btn = e.currentTarget;
    if (!navigator.clipboard) return;
    navigator.clipboard.writeText(url).then(function () {
        var orig = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>';
        btn.classList.add('border-green-500', 'bg-green-50', 'text-green-600');
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.classList.remove('border-green-500', 'bg-green-50', 'text-green-600');
        }, 2000);
    });
}
</script>

@endsection