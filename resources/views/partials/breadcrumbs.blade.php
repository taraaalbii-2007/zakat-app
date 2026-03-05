@php
    $defaultBreadcrumbs = ['Dashboard' => null];
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@if(count($breadcrumbs) > 0)
<nav class="mb-6" aria-label="Breadcrumb">
    <div class="w-full flex items-center gap-1.5 px-5 py-3.5
                bg-white border border-neutral-200
                rounded-xl shadow-sm">

        {{-- Home Icon --}}
        <a href="{{ url('/') }}"
           class="flex items-center shrink-0
                  text-neutral-500 hover:text-neutral-800
                  transition-colors duration-150">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
        </a>

        @foreach($breadcrumbs as $label => $url)

            {{-- Separator --}}
            <span class="text-neutral-400 text-lg leading-none select-none">›</span>

            @if($url && !$loop->last)
                <a href="{{ $url }}"
                   class="text-sm font-medium text-neutral-400 hover:text-neutral-700
                          transition-colors duration-150 whitespace-nowrap">
                    {{ $label }}
                </a>
            @else
                <span class="text-sm font-medium text-neutral-700 whitespace-nowrap">
                    {{ $label }}
                </span>
            @endif

        @endforeach

        @hasSection('breadcrumb-current')
            <span class="text-neutral-400 text-lg leading-none select-none">›</span>
            <span class="text-sm font-medium text-neutral-700 whitespace-nowrap">
                @yield('breadcrumb-current')
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