@props([
    'src' => null,
    'name' => '',
    'size' => 'md',
    'class' => '',
])

@php
    $sizes = [
        'sm' => 'h-8 w-8 text-sm',
        'md' => 'h-10 w-10 text-base',
        'lg' => 'h-12 w-12 text-lg',
        'xl' => 'h-16 w-16 text-2xl',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-full {$sizeClass} overflow-hidden flex-shrink-0 {$class}"]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="h-full w-full object-cover">
    @else
        <x-avatar :name="$name" :size="$size" class="h-full w-full" />
    @endif
</div>