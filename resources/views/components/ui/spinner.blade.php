{{--
  Loading Spinner Component

  Usage:
  <x-ui.spinner size="sm" color="white" />
  <x-ui.spinner size="md" color="primary" />
--}}

@props([
    'size' => 'md',
    'color' => 'primary',
])

@php
    $sizeClasses = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-6 h-6',
        'lg' => 'w-8 h-8',
        'xl' => 'w-12 h-12',
    ];

    $colorClasses = [
        'primary' => 'border-[#68C4CF]',
        'white' => 'border-white',
        'gray' => 'border-gray-600',
        'orange' => 'border-[#FF8C42]',
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
    $colorClass = $colorClasses[$color] ?? $colorClasses['primary'];
@endphp

<div {{ $attributes->merge(['class' => "inline-block $sizeClass border-2 border-t-transparent rounded-full animate-spin $colorClass"]) }}
    role="status" aria-label="Loading">
    <span class="sr-only">Loading...</span>
</div>
