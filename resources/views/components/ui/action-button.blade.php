@props([
    'icon' => 'plus',
    'iconColor' => '#FF8C42',
    'iconBg' => 'orange',
    'label' => 'Action',
    'onclick' => null,
])

<button class="action-btn bg-white rounded-2xl p-4 text-center shadow-sm"
    @if ($onclick) onclick="{{ $onclick }}" @endif>
    <div class="bg-{{ $iconBg }}-100 w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-2">
        <x-ui.icon :name="$icon" size="w-6 h-6" :color="$iconColor" />
    </div>
    <p class="text-xs font-semibold text-gray-700">{{ $label }}</p>
</button>
