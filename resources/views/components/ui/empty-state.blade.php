@props([
    'icon' => 'info',
    'iconColor' => 'text-gray-400',
    'message' => 'No data available',
])

<div class="text-center py-8">
    <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
        <x-ui.icon :name="$icon" size="w-10 h-10" color="currentColor" class="{{ $iconColor }}" />
    </div>
    <p class="text-sm text-gray-500">{{ $message }}</p>
</div>
