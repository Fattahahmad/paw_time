@props([
    'title' => 'Stat Title',
    'value' => '0',
    'icon' => '📊',
    'trend' => null,
    'trendValue' => null,
    'color' => 'cyan'
])

@php
    $colorClasses = [
        'cyan' => 'from-[#68C4CF] to-[#5AB0BB]',
        'orange' => 'from-[#FFD4B2] to-[#FFA07A]',
        'green' => 'from-[#86EFAC] to-[#4ADE80]',
        'purple' => 'from-[#C4B5FD] to-[#A78BFA]',
        'pink' => 'from-[#FBCFE8] to-[#F472B6]',
        'blue' => 'from-[#93C5FD] to-[#3B82F6]',
    ];
    $gradientClass = $colorClasses[$color] ?? $colorClasses['cyan'];
@endphp

<div class="admin-stat-card bg-white rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-gray-500 text-sm font-medium">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ $value }}</h3>
            
            @if($trend && $trendValue)
                <div class="flex items-center mt-2 space-x-1">
                    @if($trend === 'up')
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                        <span class="text-green-500 text-sm font-medium">{{ $trendValue }}</span>
                    @else
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        <span class="text-red-500 text-sm font-medium">{{ $trendValue }}</span>
                    @endif
                    <span class="text-gray-400 text-sm">vs last month</span>
                </div>
            @endif
        </div>
        
        <div class="w-14 h-14 bg-gradient-to-br {{ $gradientClass }} rounded-2xl flex items-center justify-center shadow-lg">
            <span class="text-2xl">{{ $icon }}</span>
        </div>
    </div>
</div>
