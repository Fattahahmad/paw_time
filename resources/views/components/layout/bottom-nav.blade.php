{{--
  Bottom Navigation Component
  5-button navigation untuk user dashboard dengan active state berdasarkan route
--}}

@php
    $currentRoute = Route::currentRouteName();
@endphp

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-around">
        <a href="{{ route('user.dashboard') }}"
            class="{{ $currentRoute === 'user.dashboard' ? 'bg-[#FF8C42] text-white rounded-full p-4 -mt-8 shadow-lg' : 'flex flex-col items-center space-y-1 text-gray-400' }}">
            <x-ui.icon name="home" size="w-6 h-6" color="currentColor" />
            @if ($currentRoute !== 'user.dashboard')
                <span class="text-xs font-semibold">Home</span>
            @endif
        </a>

        <a href="{{ route('user.chart') }}"
            class="{{ $currentRoute === 'user.chart' ? 'bg-[#FF8C42] text-white rounded-full p-4 -mt-8 shadow-lg' : 'flex flex-col items-center space-y-1 text-gray-400' }}">
            <x-ui.icon name="chart" size="w-6 h-6" color="currentColor" />
            @if ($currentRoute !== 'user.chart')
                <span class="text-xs font-semibold">Chart</span>
            @endif
        </a>

        <button class="flex flex-col items-center space-y-1 text-gray-400">
            <x-ui.icon name="alarm" size="w-6 h-6" color="currentColor" />
            <span class="text-xs font-semibold">Reminder</span>
        </button>

        <button class="flex flex-col items-center space-y-1 text-gray-400">
            <x-ui.icon name="meds" size="w-6 h-6" color="currentColor" />
            <span class="text-xs font-semibold">Health</span>
        </button>

        <button class="flex flex-col items-center space-y-1 text-gray-400">
            <x-ui.icon name="user" size="w-6 h-6" color="currentColor" />
            <span class="text-xs font-semibold">Profile</span>
        </button>
    </div>
</nav>
