{{-- Admin Header --}}
<header class="admin-header bg-white shadow-sm sticky top-0 z-30">
    <div class="flex items-center justify-between px-6 py-4">
        {{-- Left: Toggle Button + Page Title --}}
        <div class="flex items-center space-x-4">
            {{-- Paw Toggle Button --}}
            <button onclick="toggleAdminSidebar()" 
                    class="paw-toggle-btn w-10 h-10 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center shadow-md hover:shadow-lg transition-all duration-300 hover:scale-105">
                <span class="text-xl transform transition-transform duration-300" id="pawToggleIcon">🐾</span>
            </button>

            {{-- Page Title --}}
            <div>
                <h1 class="text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-gray-500">@yield('page-subtitle', 'Welcome back, Admin!')</p>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="flex items-center space-x-4">
            {{-- Search --}}
            <div class="hidden md:flex items-center bg-gray-100 rounded-xl px-4 py-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" placeholder="Search..." class="bg-transparent border-none outline-none ml-2 text-sm text-gray-600 w-40">
            </div>

            {{-- Notifications --}}
            <button class="relative w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
            </button>

            {{-- Profile Dropdown --}}
            <div class="relative">
                <button class="flex items-center space-x-2 hover:bg-gray-100 rounded-xl p-2 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#FFD4B2] to-[#FFA07A] rounded-xl flex items-center justify-center">
                        <span class="text-white font-semibold">A</span>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>
