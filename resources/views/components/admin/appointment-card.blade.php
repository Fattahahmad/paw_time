@props(['appointment'])

<a href="{{ route('admin.appointments.show', $appointment) }}" 
   class="block bg-white rounded-xl p-4 hover:shadow-md transition-shadow border border-gray-200">
    <div class="flex items-start justify-between mb-2">
        <div class="flex-1">
            <p class="font-semibold text-gray-800">{{ $appointment->pet->pet_name }}</p>
            <p class="text-sm text-gray-600">{{ $appointment->user->name }}</p>
        </div>
        <span class="text-xl">
            @if(strtolower($appointment->pet->species) === 'cat')
                🐱
            @elseif(strtolower($appointment->pet->species) === 'dog')
                🐶
            @elseif(strtolower($appointment->pet->species) === 'bird')
                🦜
            @elseif(strtolower($appointment->pet->species) === 'rabbit')
                🐰
            @else
                🐾
            @endif
        </span>
    </div>
    <div class="text-sm text-gray-600 mb-2">
        <p>📅 {{ $appointment->appointment_date->format('d M Y') }}</p>
        <p>🕐 {{ $appointment->appointment_date->format('H:i') }}</p>
    </div>
    @if($appointment->notes)
        <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $appointment->notes }}</p>
    @endif
</a>
