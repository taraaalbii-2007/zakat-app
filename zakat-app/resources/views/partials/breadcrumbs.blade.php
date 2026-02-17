@php
    // Default breadcrumbs jika tidak di-set
    $defaultBreadcrumbs = [
        'Dashboard' => null,
    ];
    
    $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
    $currentPage = $currentPage ?? Request::route()->getName();
@endphp

@if(count($breadcrumbs) > 0)
<nav class="mb-6 animate-fade-in" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center text-sm">
        <!-- Home Link -->
        <li class="inline-flex items-center">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center text-neutral-500 hover:text-primary-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                Beranda
            </a>
            @if(count($breadcrumbs) > 0)
                <svg class="w-4 h-4 mx-2 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            @endif
        </li>

        <!-- Dynamic Breadcrumbs -->
        @foreach($breadcrumbs as $label => $url)
            <li class="inline-flex items-center">
                @if($url && !$loop->last)
                    <a href="{{ $url }}" 
                       class="text-neutral-500 hover:text-primary-600 transition-colors">
                        {{ $label }}
                    </a>
                    <svg class="w-4 h-4 mx-2 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <span class="text-neutral-900 font-medium">{{ $label }}</span>
                @endif
            </li>
        @endforeach

        <!-- Current Page (from section) -->
        @hasSection('breadcrumb-current')
            <li class="inline-flex items-center">
                <svg class="w-4 h-4 mx-2 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                <span class="text-neutral-900 font-medium">@yield('breadcrumb-current')</span>
            </li>
        @endif
    </ol>
    
    <!-- Page Title (if not in navbar) -->
    @hasSection('page-title')
        <h1 class="text-2xl font-bold text-neutral-900 mt-2">@yield('page-title')</h1>
    @endif
    
    @hasSection('page-description')
        <p class="text-neutral-600 mt-1">@yield('page-description')</p>
    @endif
</nav>
@endif

<!-- Example of usage in view:
@section('breadcrumb-current', 'Kalkulator Zakat')
@section('page-title', 'Kalkulator Zakat')
@section('page-description', 'Hitung zakat Anda dengan mudah dan akurat')

Or pass from controller:
$breadcrumbs = [
    'Dashboard' => route('dashboard'),
    'Zakat' => route('zakat.index'),
    'Kalkulator' => null,
];
-->