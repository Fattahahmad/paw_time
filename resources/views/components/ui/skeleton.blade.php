{{--
  Skeleton Loading Component

  Usage:
  <x-ui.skeleton type="card" :count="3" />
  <x-ui.skeleton type="list" height="60px" />
  <x-ui.skeleton type="text" width="200px" />
--}}

@props([
    'type' => 'rectangle',
    'count' => 1,
    'height' => null,
    'width' => null,
])

@php
    $baseClass = 'skeleton-loader animate-pulse bg-gray-200 rounded-lg';

    $typeClasses = [
        'card' => 'w-full h-48',
        'list' => 'w-full h-16',
        'text' => 'w-full h-4',
        'circle' => 'w-12 h-12 rounded-full',
        'rectangle' => 'w-full h-32',
        'avatar' => 'w-16 h-16 rounded-full',
        'button' => 'w-32 h-10 rounded-2xl',
    ];

    $typeClass = $typeClasses[$type] ?? $typeClasses['rectangle'];

    $customStyle = [];
    if ($height) {
        $customStyle[] = "height: $height";
    }
    if ($width) {
        $customStyle[] = "width: $width";
    }
    $styleAttr = !empty($customStyle) ? 'style="' . implode('; ', $customStyle) . '"' : '';
@endphp

@for ($i = 0; $i < $count; $i++)
    @if ($type === 'card')
        {{-- Skeleton Card --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm space-y-3 {{ $i < $count - 1 ? 'mb-4' : '' }}">
            <div class="skeleton-loader animate-pulse bg-gray-200 rounded-xl w-full h-32"></div>
            <div class="space-y-2">
                <div class="skeleton-loader animate-pulse bg-gray-200 rounded h-4 w-3/4"></div>
                <div class="skeleton-loader animate-pulse bg-gray-200 rounded h-3 w-1/2"></div>
            </div>
            <div class="flex space-x-2">
                <div class="skeleton-loader animate-pulse bg-gray-200 rounded-full w-8 h-8"></div>
                <div class="skeleton-loader animate-pulse bg-gray-200 rounded-full w-8 h-8"></div>
            </div>
        </div>
    @elseif ($type === 'list')
        {{-- Skeleton List Item --}}
        <div class="flex items-center space-x-4 p-4 bg-white rounded-2xl shadow-sm {{ $i < $count - 1 ? 'mb-3' : '' }}">
            <div class="skeleton-loader animate-pulse bg-gray-200 rounded-2xl w-12 h-12"></div>
            <div class="flex-1 space-y-2">
                <div class="skeleton-loader animate-pulse bg-gray-200 rounded h-4 w-3/4"></div>
                <div class="skeleton-loader animate-pulse bg-gray-200 rounded h-3 w-1/2"></div>
            </div>
            <div class="skeleton-loader animate-pulse bg-gray-200 rounded w-6 h-6"></div>
        </div>
    @elseif ($type === 'text')
        {{-- Skeleton Text Line --}}
        <div class="{{ $baseClass }} {{ $typeClass }} {{ $i < $count - 1 ? 'mb-2' : '' }}"
            {!! $styleAttr !!}></div>
    @else
        {{-- Skeleton Generic --}}
        <div class="{{ $baseClass }} {{ $typeClass }} {{ $i < $count - 1 ? 'mb-4' : '' }}"
            {!! $styleAttr !!}></div>
    @endif
@endfor
