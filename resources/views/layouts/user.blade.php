<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Paw Time - Dashboard')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Toastify CSS --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    {{-- Alpine.js for dropdown --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Toastify JS --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    {{-- Firebase SDK (must use compat version for messaging) --}}
    <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js"></script>
    <script src="{{ url('js/notifications.js') }}"></script>

    @stack('styles')
</head>

<body class="pb-20 bg-gray-50">
    {{-- Background Bubbles --}}
    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; bottom: 20%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; right: 15%; animation-delay: 2s;"></div>

    {{-- User Header --}}
    <x-layout.user-navbar />

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-6 py-6">
        {{-- Success/Error Messages --}}
        @if(session('success'))
            <x-ui.form-success :message="session('success')" />
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        @foreach($errors->all() as $error)
                            <p class="text-sm font-medium text-red-800">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <x-layout.bottom-nav />

    @stack('modals')
    @stack('scripts')

    {{-- Notification Initialization --}}
    <script>
        // VAPID Key for Firebase Web Push (get from Firebase Console > Cloud Messaging > Web Push certificates)
        window.VAPID_KEY = "{{ config('services.firebase.vapid_key', '') }}";

        // Service Worker base path (important for subfolder installations)
        window.SW_BASE_PATH = "{{ rtrim(asset(''), '/') }}".replace(window.location.origin, '');
        console.log('📂 Base path for SW:', window.SW_BASE_PATH);

        // Initialize notifications when page loads
        document.addEventListener('DOMContentLoaded', async function() {
            console.log('🔄 DOM Content Loaded - Initializing notifications...');

            // Wait a bit for script to load
            await new Promise(resolve => setTimeout(resolve, 100));

            console.log('PawTimeNotifications type:', typeof PawTimeNotifications);
            console.log('PawTimeNotifications value:', PawTimeNotifications);

            if (typeof PawTimeNotifications !== 'undefined' && PawTimeNotifications && typeof PawTimeNotifications.initialize === 'function') {
                console.log('✅ PawTimeNotifications loaded correctly');

                const initialized = await PawTimeNotifications.initialize();
                if (initialized) {
                    console.log('🔔 Paw Time Notifications ready');

                    // Check if already has permission
                    if (Notification.permission === 'granted') {
                        const token = await PawTimeNotifications.getToken();
                        if (token) {
                            console.log('FCM Token available');

                            // Auto-register token if user already gave permission
                            try {
                                console.log('📤 Auto-registering existing token...');
                                const response = await fetch('/notifications/register', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({
                                        token: token,
                                        device_type: 'web',
                                        device_name: navigator.userAgent.substring(0, 100)
                                    })
                                });

                                if (response.ok) {
                                    const data = await response.json();
                                    console.log('✅ Token auto-registered:', data);
                                }
                            } catch (error) {
                                console.error('❌ Error auto-registering token:', error);
                            }
                        }
                    }
                }
            } else {
                console.error('❌ PawTimeNotifications not loaded properly');
                console.error('- Type:', typeof PawTimeNotifications);
                console.error('- Has initialize?', typeof PawTimeNotifications?.initialize);
            }
        });

        // Global function to enable notifications
        window.enableNotifications = async function() {
            console.log('🔔 enableNotifications() called');
            console.log('PawTimeNotifications:', typeof PawTimeNotifications, PawTimeNotifications);

            if (typeof PawTimeNotifications === 'undefined' || !PawTimeNotifications) {
                console.error('❌ PawTimeNotifications not loaded');
                alert('Notification service not loaded. Please refresh the page.');
                return;
            }

            if (typeof PawTimeNotifications.initialize !== 'function') {
                console.error('❌ PawTimeNotifications.initialize is not a function!');
                console.error('PawTimeNotifications:', PawTimeNotifications);
                alert('Notification service error. Please refresh the page.');
                return;
            }

            // First ensure initialization
            if (!PawTimeNotifications.isInitialized) {
                console.log('🔄 Initializing notifications...');
                const initialized = await PawTimeNotifications.initialize();
                if (!initialized) {
                    alert('Gagal menginisialisasi notifikasi. Cek console untuk detail.');
                    return;
                }
            }

            console.log('🔄 Requesting permission...');
            const token = await PawTimeNotifications.requestPermission();

            if (token) {
                console.log('✅ FCM Token obtained:', token);

                // Register token to backend
                try {
                    console.log('📤 Registering token to backend...');
                    const response = await fetch('/notifications/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            token: token,
                            device_type: 'web',
                            device_name: navigator.userAgent.substring(0, 100)
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();
                        console.log('✅ Token registered to backend:', data);
                    } else {
                        const errorText = await response.text();
                        console.error('❌ Failed to register token:', response.status, errorText);
                    }
                } catch (error) {
                    console.error('❌ Error registering token:', error);
                }

                // Hide notification banner if exists
                const banner = document.getElementById('notificationBanner');
                if (banner) {
                    banner.style.transition = 'opacity 0.3s ease';
                    banner.style.opacity = '0';
                    setTimeout(() => banner.classList.add('hidden'), 300);
                }

                // Show success toast
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "🔔 Notifikasi berhasil diaktifkan!",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #10b981, #059669)",
                        stopOnFocus: true,
                    }).showToast();
                } else {
                    alert('Notifikasi berhasil diaktifkan!');
                }

                // Copy token to clipboard for testing
                navigator.clipboard?.writeText(token);
                console.log('📋 Token copied to clipboard');

                // Show token for debugging (can remove in production)
                console.log('📱 Full FCM Token:', token);
            } else {
                console.error('❌ Failed to get FCM token');
                alert('Gagal mengaktifkan notifikasi. Buka Console (F12) untuk melihat error detail.');
            }
        };
    </script>

    {{-- Toast Notifications --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: "{{ session('success') }}",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #10b981, #059669)",
                        stopOnFocus: true,
                    }).showToast();
                }
            });
        </script>
    @endif
</body>

</html>
