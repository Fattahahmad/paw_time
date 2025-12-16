{{--
  Navigation Link Component

  Usage:
  @include('components.nav-link', [
      'text' => 'Home',
      'href' => '#home',
      'active' => false  // optional: set active state
  ])
--}}

<a href="{{ $href }}"
    class="nav-link text-gray-700 hover:text-[#68C4CF] font-medium transition-colors {{ isset($active) && $active ? 'text-[#68C4CF]' : '' }}">
    {{ $text }}
</a>
