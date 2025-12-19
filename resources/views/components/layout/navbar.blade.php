<nav class="relative z-50 px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- Logo --}}
        <div class="flex items-center space-x-3 flex-shrink-0">
            <x-ui.icon name="paw" size="w-10 h-10" color="#FF8C42" />
            <span class="text-2xl font-bold text-gray-800">Paw Time</span>
        </div>

        {{-- Nav Links (Center) --}}
        <div class="hidden md:flex space-x-6 flex-1 justify-center">
            <x-ui.nav-link text="Home" href="#home" />
            <x-ui.nav-link text="Features" href="#features" />
            <x-ui.nav-link text="About" href="#about" />
            <x-ui.nav-link text="Contact" href="#contact" />
        </div>

        {{-- Buttons (Right) --}}
        <div class="flex items-center space-x-3 flex-shrink-0">
            <x-ui.button text="Download" type="primary" size="md" />
            <x-ui.button text="Register" type="white" size="md" href="{{ route('auth.login') }}" />
        </div>

    </div>
</nav>
