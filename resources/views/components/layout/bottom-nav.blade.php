{{--
  Bottom Navigation Component
  5-button navigation untuk user dashboard
--}}

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-around">
        <button class="bg-[#FF8C42] text-white rounded-full p-4 -mt-8 shadow-lg">
            <x-ui.icon name="home" size="w-6 h-6" color="currentColor" />
        </button>

        <button class="flex flex-col items-center space-y-1 text-gray-400">
            <x-ui.icon name="chart" size="w-6 h-6" color="currentColor" />
            <span class="text-xs font-semibold">Chart</span>
        </button>

        <button class="flex flex-col items-center space-y-1 text-gray-400">
            <x-ui.icon name="alarm" size="w-6 h-6" color="currentColor" />
            <span class="text-xs font-semibold">Reminder</span>
        </button>

        {{-- <button class="flex flex-col items-center space-y-1 text-gray-400" onclick="openAddPetModal()">
            <x-ui.icon name="plus" size="w-6 h-6" color="currentColor" />
            <span class="text-xs font-semibold">Add</span>
        </button> --}}

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
