<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Paw Time</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="gradient-bg">

    <x-layout.navbar />

    <main>
        @yield('content')
    </main>

    <x-layout.footer />
</body>

</html>
