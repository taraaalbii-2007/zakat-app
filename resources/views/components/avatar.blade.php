@props([
    'name' => '',
    'size' => 'md', // sm, md, lg, xl
    'class' => '',
    'bgColor' => null, // jika ingin custom warna
    'textColor' => null, // jika ingin custom warna text
])

@php
    // Daftar warna background yang tersedia
    $colors = [
        'bg-blue-500',
        'bg-green-500',
        'bg-yellow-500',
        'bg-red-500',
        'bg-purple-500',
        'bg-pink-500',
        'bg-indigo-500',
        'bg-orange-500',
        'bg-teal-500',
        'bg-cyan-500',
    ];
    
    // Dapatkan inisial dari nama
    $initial = strtoupper(substr($name, 0, 1));
    
    // Tentukan warna berdasarkan huruf pertama untuk konsistensi
    $colorIndex = $initial ? (ord($initial) - 65) % count($colors) : 0;
    $bgColorClass = $bgColor ?? $colors[$colorIndex];
    
    // Ukuran avatar
    $sizes = [
        'sm' => 'h-8 w-8 text-sm',
        'md' => 'h-10 w-10 text-base',
        'lg' => 'h-12 w-12 text-lg',
        'xl' => 'h-16 w-16 text-2xl',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-full {$sizeClass} flex items-center justify-center font-medium text-white {$bgColorClass} {$class}"]) }}>
    {{ $initial }}
</div>