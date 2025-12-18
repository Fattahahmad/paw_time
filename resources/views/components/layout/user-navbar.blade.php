{{--
  User Navbar Component
  Header untuk authenticated user pages dengan notification bell
--}}

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <button class="md:hidden" onclick="toggleSidebar()">
                <x-ui.icon name="menu" size="w-6 h-6" color="currentColor" />
            </button>
            <h1 class="text-xl font-bold text-gray-800">Paw Time</h1>
        </div>
        <button class="relative">
            <x-ui.icon name="bell" size="w-6 h-6" color="currentColor" class="text-gray-700" />
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
        </button>
    </div>
</header>
