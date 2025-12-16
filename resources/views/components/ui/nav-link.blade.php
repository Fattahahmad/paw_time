{{--
  Navigation Link Component

  Usage:
  <x-ui.nav-link text="Home" href="#home" />
  <x-ui.nav-link text="About" href="#about" :active="true" />
--}}

@props([
    'text' => '',
    'href' => '#',
    'active' => false,
])

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'nav-link text-gray-700 hover:text-[#68C4CF] font-medium transition-colors ' . ($active ? 'text-[#68C4CF]' : '')]) }}>
    {{ $text }}
</a>
