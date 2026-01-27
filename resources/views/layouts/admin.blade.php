<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Paw Time - Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="admin-wrapper flex">
        {{-- Sidebar --}}
        <x-admin.sidebar />

        {{-- Main Content --}}
        <div class="admin-main flex-1 transition-all duration-300" id="adminMain">
            {{-- Top Header --}}
            <x-admin.header />

            {{-- Page Content --}}
            <main class="p-6 page-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Sidebar Overlay for Mobile --}}
    <div class="sidebar-overlay fixed inset-0 bg-black/50 z-40 hidden" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

    {{-- Admin Scripts --}}
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const mainContent = document.getElementById('adminMain');
            const overlay = document.getElementById('sidebarOverlay');
            const icon = document.getElementById('pawToggleIcon');
            
            // For desktop
            if (window.innerWidth >= 1024) {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                // Animate icon
                if (sidebar.classList.contains('collapsed')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            } else {
                // For mobile
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                
                // Animate icon
                if (sidebar.classList.contains('show')) {
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.querySelector('.paw-toggle-btn');
            
            if (window.innerWidth < 1024 && 
                sidebar.classList.contains('show') && 
                !sidebar.contains(event.target) && 
                !toggleBtn.contains(event.target)) {
                toggleAdminSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('adminSidebar');
            const mainContent = document.getElementById('adminMain');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth >= 1024) {
                // Remove mobile classes
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            } else {
                // Remove desktop classes
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        });
    </script>
</body>

</html>
