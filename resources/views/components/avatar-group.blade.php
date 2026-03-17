@props([
    'items' => [],
    'limit' => 3,
    'size' => 'md',
    'showCount' => true,
])

@php
    $displayItems = array_slice($items, 0, $limit);
    $remainingCount = count($items) - $limit;
@endphp

<div {{ $attributes->merge(['class' => 'flex -space-x-2 overflow-hidden']) }}>
    @foreach($displayItems as $item)
        <x-avatar 
            :name="$item['name'] ?? $item" 
            :size="$size"
            class="ring-2 ring-white"
        />
    @endforeach
    
    @if($showCount && $remainingCount > 0)
        <div class="flex items-center justify-center {{ $size == 'sm' ? 'h-8 w-8 text-xs' : ($size == 'md' ? 'h-10 w-10 text-sm' : 'h-12 w-12 text-base') }} rounded-full bg-gray-100 text-gray-600 font-medium ring-2 ring-white">
            +{{ $remainingCount }}
        </div>
    @endif
</div>