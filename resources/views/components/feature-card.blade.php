<div class="card bg-white rounded-3xl p-8 shadow-lg">
    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="{{ $icon }}" />
        </svg>
    </div>
    <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $title }}</h3>
    <p class="text-gray-600">
        {{ $description }}
    </p>
</div>
