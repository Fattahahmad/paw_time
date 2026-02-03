@props([
    'title' => 'Reminder Title',
    'detail' => null,
    'date' => null,
    'time' => null,
    'icon' => 'cart',
    'iconBg' => 'orange',
    'iconColor' => '#FF8C42',
    'borderColor' => 'orange',
    'showActions' => true,
    'actions' => null, // custom actions slot
])

<div
    class="reminder-card bg-white rounded-2xl p-4 shadow-sm @if ($showActions) mb-3 @endif border-l-4 border-{{ $borderColor }}-400">
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-start space-x-3 flex-1">
            <div class="bg-{{ $iconBg }}-100 p-2.5 rounded-xl">
                <x-ui.icon :name="$icon" size="w-6 h-6" color="currentColor" class="text-{{ $iconBg }}-500" />
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-gray-800 mb-1">{{ $title }}</h3>
                @if ($detail)
                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                        <span>{{ $detail }}</span>
                    </div>
                @endif
            </div>
        </div>
        @if ($time || $date)
            <div class="text-right">
                @if ($date)
                    <p class="text-xs font-semibold text-gray-700">{{ $date }}</p>
                @endif
                @if ($time)
                    <p
                        class="text-xs text-gray-500 @if ($date) mt-1 @else bg-gray-100 px-2 py-1 rounded-lg @endif">
                        {{ $time }}</p>
                @endif
            </div>
        @endif
    </div>

    @if ($showActions)
        <div class="flex items-center space-x-2 mt-2">
            {{ $actions ?? '' }}
        </div>
    @endif
</div>
