{{--
  Button Component

  Usage:
  <x-ui.button text="Click Me" type="primary" size="md" />
  <x-ui.button text="Link Button" href="#" type="white" />
--}}

@props([
    'text' => 'Button',
    'type' => 'primary',
    'size' => 'md',
    'href' => null,
    'onclick' => null,
])

@php
    $typeClasses = [
        'primary' => 'btn-primary text-white',
        'white' => 'btn-white text-black',
        'secondary' => 'btn-secondary text-[#68C4CF]',
    ];

    $sizeClasses = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-2.5 text-base',
        'lg' => 'px-8 py-4 text-lg',
    ];

    $baseClasses = 'rounded-full font-semibold transition-all inline-flex items-center justify-center';
    $typeClass = $typeClasses[$type] ?? $typeClasses['primary'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "$baseClasses $typeClass $sizeClass"]) }}>
        {{ $text }}
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => "$baseClasses $typeClass $sizeClass"]) }}
        @if ($onclick) onclick="{{ $onclick }}" @endif>
        {{ $text }}
    </button>
@endif
