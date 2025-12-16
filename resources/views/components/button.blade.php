{{--
  Button Component

  Usage:
  @include('components.button', [
      'text' => 'Click Me',
      'type' => 'primary',        // primary, white, secondary
      'size' => 'md',             // sm, md, lg
      'href' => '#',              // optional: if set, renders <a> instead of <button>
      'onclick' => 'alert(...)',  // optional: onclick handler
      'class' => 'custom-class'   // optional: additional classes
  ])
--}}

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
    $typeClass = $typeClasses[$type ?? 'primary'] ?? $typeClasses['primary'];
    $sizeClass = $sizeClasses[$size ?? 'md'] ?? $sizeClasses['md'];
    $finalClass = "$baseClasses $typeClass $sizeClass " . ($class ?? '');
@endphp

@if (isset($href))
    <a href="{{ $href }}" class="{{ $finalClass }}" {{ isset($class) ? '' : '' }}>
        {{ $text }}
    </a>
@else
    <button type="button" class="{{ $finalClass }}"
        @if (isset($onclick)) onclick="{{ $onclick }}" @endif>
        {{ $text }}
    </button>
@endif
