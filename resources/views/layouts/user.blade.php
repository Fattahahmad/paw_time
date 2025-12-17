<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Paw Time - Dashboard')</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <x-layout.bottom-nav />

    @stack('modals')
    @stack('scripts')
</body>

</html>
