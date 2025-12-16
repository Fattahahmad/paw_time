{{--
  Step Item Component

  Usage:
  <x-cards.step-item number="1" bgColor="bg-[#68C4CF]" title="Download App" description="Get Paw Time from App Store" />
--}}

@props([
    'number' => '1',
    'bgColor' => 'bg-[#68C4CF]',
    'title' => '',
    'description' => '',
])

<div {{ $attributes->merge(['class' => 'text-center']) }}>
    <div class="{{ $bgColor }} w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
        <span class="text-3xl font-bold text-white">{{ $number }}</span>
    </div>
    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $title }}</h3>
    <p class="text-gray-600">{{ $description }}</p>
</div>
