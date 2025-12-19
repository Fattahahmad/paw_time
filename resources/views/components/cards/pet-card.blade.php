@props([
    'name' => 'Pet Name',
    'breed' => 'Breed',
    'age' => '0',
    'type' => 'Pet',
    'icon' => 'paw',
    'iconBg' => 'orange',
    'iconColor' => '#FF8C42',
    'typeColor' => '#68C4CF',
    'onclick' => null,
])

<div class="pet-card bg-white rounded-2xl p-4 shadow-sm flex items-center justify-between cursor-pointer"
    @if ($onclick) onclick="{{ $onclick }}" @endif>
    <div class="flex items-center space-x-4">
        <div class="bg-{{ $iconBg }}-100 p-3 rounded-2xl">
            <x-ui.icon :name="$icon" size="w-8 h-8" :color="$iconColor" />
        </div>
        <div>
            <h3 class="font-bold text-gray-800">{{ $name }}</h3>
            <p class="text-sm text-gray-500">{{ $breed }}</p>
            <p class="text-xs text-gray-400">{{ $age }} Age • <span
                    style="color: {{ $typeColor }}">{{ $type }}</span></p>
        </div>
    </div>
    <x-ui.icon name="chevron-right" size="w-6 h-6" color="currentColor" class="text-gray-400" />
</div>
