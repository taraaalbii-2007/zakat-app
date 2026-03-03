@php
    $defaultBreadcrumbs = ['Dashboard' => null];
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@if(count($breadcrumbs) > 0)
<nav class="mb-6" aria-label="Breadcrumb">
    <div class="w-full flex items-center gap-2 px-4 py-2.5
                bg-white border border-neutral-200/70
                rounded-xl shadow-sm
                ring-1 ring-inset ring-white/80">

        {{-- Home --}}
        <a href="{{ url('/') }}"
           class="group flex items-center gap-2 shrink-0
                  text-xs font-medium text-neutral-400
                  hover:text-green-600 transition-all duration-200">
            <span class="flex items-center justify-center w-6 h-6 rounded-lg
                         bg-neutral-100 text-neutral-400
                         group-hover:bg-green-50 group-hover:text-green-600
                         transition-all duration-200 shadow-inner">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
            </span>
            <span class="hidden sm:inline tracking-wide">Beranda</span>
        </a>

        @foreach($breadcrumbs as $label => $url)

            {{-- Separator --}}
            <svg class="w-3 h-3 shrink-0 text-neutral-300" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>

            @if($url && !$loop->last)
                <a href="{{ $url }}"
                   class="text-xs font-medium text-neutral-400 hover:text-green-600
                          transition-colors duration-200 whitespace-nowrap tracking-wide">
                    {{ $label }}
                </a>
            @else
                {{-- Active page — inline, same flow --}}
                <span class="flex items-center gap-2 whitespace-nowrap">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full
                                     rounded-full bg-green-400 opacity-60"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-xs font-bold text-neutral-800 tracking-wide">
                        {{ $label }}
                    </span>
                </span>
            @endif

        @endforeach

        @hasSection('breadcrumb-current')
            <svg class="w-3 h-3 shrink-0 text-neutral-300" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="flex items-center gap-2 whitespace-nowrap">
                <span class="relative flex h-2 w-2 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full
                                 rounded-full bg-green-400 opacity-60"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                <span class="text-xs font-bold text-neutral-800 tracking-wide">
                    @yield('breadcrumb-current')
                </span>
            </span>
        @endif

    </div>

    @hasSection('page-title')
        <h1 class="text-2xl font-bold text-neutral-900 mt-3 tracking-tight">@yield('page-title')</h1>
    @endif
    @hasSection('page-description')
        <p class="text-sm text-neutral-500 mt-1">@yield('page-description')</p>
    @endif
</nav>
@endif