<section id="home" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
    <div class="grid md:grid-cols-2 gap-12 items-center">
        <div class="space-y-6">
            <h1 class="text-5xl md:text-6xl font-bold text-gray-800 leading-tight">
                Take Care Of Your<br>
                <span class="text-[#FF8C42]">Beloved Pet</span>
            </h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Waktunya bikin jadwal makan si kucing jadi lebih teratur.
                Dengan PawTime, kucingmu selalu terjaga dan ceria! 🐱
            </p>
            <div class="flex flex-wrap gap-4">
                @include('components.button', [
                    'text' => 'Get Started',
                    'type' => 'primary',
                    'size' => 'lg',
                ])
                @include('components.button', [
                    'text' => 'Learn More',
                    'type' => 'white',
                    'size' => 'lg',
                ])
            </div>
            <div class="flex items-center space-x-8 pt-4">
                <div>
                    <p class="text-3xl font-bold text-[#68C4CF]">10K+</p>
                    <p class="text-gray-600">Happy Pets</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-[#FF8C42]">5K+</p>
                    <p class="text-gray-600">Active Users</p>
                </div>
            </div>
        </div>

        <div class="relative flex justify-center">
            <div class="hero-image relative z-10">
                <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=600&h=600&fit=crop"
                    alt="Cute Cat"
                    class="rounded-full shadow-2xl w-full max-w-md object-cover aspect-square border-4 border-white/30">
            </div>

            <div class="absolute -bottom-4 right-0 md:right-10 bg-white rounded-2xl p-6 shadow-xl z-20 animate-bounce"
                style="animation-duration: 3s;">
                <div class="flex items-center space-x-3">
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-[#FF8C42]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">Feeding Time!</p>
                        <p class="text-sm text-gray-500">In 30 minutes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
