{{-- Admin Sidebar --}}
<aside class="admin-sidebar bg-white shadow-lg fixed h-full z-50 transition-all duration-300" id="adminSidebar">
    {{-- Logo Section --}}
    <div class="sidebar-header p-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 sidebar-brand">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-[#68C4CF] to-[#5AB0BB] rounded-xl flex items-center justify-center shadow-md">
                    <span class="text-white text-xl">🐾</span>
                </div>
                <span class="text-xl font-bold text-gray-800 sidebar-text">Paw Time</span>
            </a>
        </div>
    </div>

    {{-- Navigation Menu --}}
    <nav class="sidebar-nav p-4 space-y-2">
        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
            </div>
            <span class="sidebar-text">Dashboard</span>
        </a>

        {{-- Users Management --}}
        <a href="{{ route('admin.users.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <span class="sidebar-text">Users</span>
        </a>

        {{-- Pets Management --}}
        <a href="{{ route('admin.pets.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.pets.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <span class="text-lg">🐕</span>
            </div>
            <span class="sidebar-text">Pets</span>
        </a>

        {{-- Reminders --}}
        <a href="{{ route('admin.reminders.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.reminders.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
            </div>
            <span class="sidebar-text">Reminders</span>
        </a>

        {{-- Health Checks --}}
        <a href="{{ route('admin.health-checks.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.health-checks.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <span class="sidebar-text">Health Checks</span>
        </a>

        {{-- Appointments --}}
        <a href="{{ route('admin.appointments.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <span class="sidebar-text">Appointments</span>
        </a>

        {{-- Divider --}}
        <div class="border-t border-gray-200 my-4"></div>

        {{-- Notification Test --}}
        <a href="{{ route('admin.notification-test.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.notification-test.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
            </div>
            <span class="sidebar-text">Test Notif</span>
        </a>

        {{-- Settings --}}
        <a href="{{ route('admin.settings.index') }}"
            class="sidebar-menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <div class="menu-icon">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <span class="sidebar-text">Settings</span>
        </a>
    </nav>

    {{-- User Info at Bottom --}}
    <div class="sidebar-footer absolute bottom-0 left-0 right-0 p-4 border-t border-gray-100 bg-white">
        <div class="flex items-center space-x-3">
            <div
                class="w-10 h-10 bg-gradient-to-br from-[#FFD4B2] to-[#FFA07A] rounded-xl flex items-center justify-center">
                <span class="text-white font-semibold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
            </div>
            <div class="sidebar-text">
                <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'admin@pawtime.com' }}</p>
            </div>
        </div>
    </div>
</aside>
