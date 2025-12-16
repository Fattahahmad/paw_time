<nav class="relative z-50 px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- Logo --}}
        <div class="flex items-center space-x-3 flex-shrink-0">
            @include('components.icon', [
                'name' => 'paw',
                'size' => 'w-10 h-10',
                'color' => '#FF8C42',
            ])
            <span class="text-2xl font-bold text-gray-800">Paw Time</span>
        </div>

        {{-- Nav Links (Center) --}}
        <div class="hidden md:flex space-x-6 flex-1 justify-center">
            @include('components.nav-link', ['text' => 'Home', 'href' => '#home'])
            @include('components.nav-link', ['text' => 'Features', 'href' => '#features'])
            @include('components.nav-link', ['text' => 'About', 'href' => '#about'])
            @include('components.nav-link', ['text' => 'Contact', 'href' => '#contact'])
        </div>

        {{-- Buttons (Right) --}}
        <div class="flex items-center space-x-3 flex-shrink-0">
            @include('components.button', [
                'text' => 'Download',
                'type' => 'primary',
                'size' => 'md',
            ])
            @include('components.button', [
                'text' => 'Register',
                'type' => 'white',
                'size' => 'md',
            ])
        </div>

    </div>
</nav>
