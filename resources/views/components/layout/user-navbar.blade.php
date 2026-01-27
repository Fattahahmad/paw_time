{{--
  User Navbar Component
  Header untuk authenticated user pages dengan notification bell dan profile dropdown
--}}

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <button class="md:hidden" onclick="toggleSidebar()">
                <x-ui.icon name="menu" size="w-6 h-6" color="currentColor" />
            </button>
            <h1 class="text-xl font-bold text-gray-800">Paw Time</h1>
        </div>
        
        <div class="flex items-center space-x-4">
            {{-- Notification Bell --}}
            <button class="relative">
                <x-ui.icon name="bell" size="w-6 h-6" color="currentColor" class="text-gray-700" />
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
            </button>
            
            {{-- Profile Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                
                {{-- Dropdown Menu --}}
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl py-2 z-50"
                     style="display: none;">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        Profile
                    </a>
                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
