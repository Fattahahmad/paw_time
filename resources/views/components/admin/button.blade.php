@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-300';
    
    $sizeClasses = [
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-5 py-3 text-sm',
        'lg' => 'px-6 py-4 text-base',
    ];
    
    $variantClasses = [
        'primary' => 'bg-gradient-to-r from-[#68C4CF] to-[#5AB0BB] text-white hover:shadow-lg hover:shadow-[#68C4CF]/30 hover:-translate-y-0.5',
        'secondary' => 'bg-gray-100 text-gray-700 hover:bg-gray-200',
        'success' => 'bg-gradient-to-r from-[#86EFAC] to-[#4ADE80] text-white hover:shadow-lg hover:shadow-green-500/30 hover:-translate-y-0.5',
        'danger' => 'bg-gradient-to-r from-[#FCA5A5] to-[#EF4444] text-white hover:shadow-lg hover:shadow-red-500/30 hover:-translate-y-0.5',
        'warning' => 'bg-gradient-to-r from-[#FFD4B2] to-[#FFA07A] text-white hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5',
        'outline' => 'border-2 border-[#68C4CF] text-[#68C4CF] hover:bg-[#68C4CF] hover:text-white',
        'ghost' => 'text-gray-600 hover:bg-gray-100',
    ];
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ' ' . $variantClasses[$variant];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="mr-2">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
