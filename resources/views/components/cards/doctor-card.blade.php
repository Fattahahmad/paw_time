@props([
    'name' => 'Doctor Name',
    'specialty' => 'Specialty',
    'rating' => '4.8',
    'image' => null,
    'onclick' => null,
])

<div class="doctor-card rounded-3xl p-4 cursor-pointer"
    @if ($onclick) onclick="{{ $onclick }}" @endif>
    <div class="bg-white rounded-2xl p-3 mb-3">
        @if ($image)
            <img src="{{ $image }}" alt="{{ $name }}" class="w-full h-32 object-cover rounded-xl mb-2">
        @else
            <div class="w-full h-32 bg-gray-200 rounded-xl mb-2 flex items-center justify-center">
                <x-ui.icon name="user" size="w-12 h-12" color="currentColor" class="text-gray-400" />
            </div>
        @endif
    </div>
    <div class="text-center">
        <h3 class="font-bold text-gray-800 text-sm mb-1">{{ $name }}</h3>
        <p class="text-xs text-gray-600 mb-2">{{ $specialty }}</p>
        <div class="flex items-center justify-center space-x-1 mb-2">
            <x-ui.icon name="star" size="w-4 h-4" color="#FCD34D" />
            <span class="text-xs font-semibold text-gray-700">{{ $rating }}</span>
        </div>
    </div>
    <div class="flex items-center justify-center space-x-2">
        {{ $slot }}
    </div>
</div>
