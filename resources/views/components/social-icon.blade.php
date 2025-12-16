{{--
  Social Icon Component

  Usage:
  @include('components.social-icon', [
      'icon' => 'facebook',  // facebook, twitter, youtube, etc
      'url' => '#',
      'color' => 'teal'      // teal, orange, cyan
  ])
--}}

@php
    $colorClasses = [
        'teal' => 'bg-[#68C4CF]',
        'orange' => 'bg-[#FF8C42]',
        'cyan' => 'bg-[#68C4CF]',
    ];

    $bgColor = $colorClasses[$color ?? 'teal'] ?? $colorClasses['teal'];
@endphp

<a href="{{ $url }}"
    class="social-icon w-10 h-10 {{ $bgColor }} rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform p-0 m-0"
    target="_blank" rel="noopener noreferrer">
    @include('components.icon', [
        'name' => $icon,
        'size' => 'w-5 h-5',
        'color' => 'currentColor',
    ])
</a>
