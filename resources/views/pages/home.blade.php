@extends('layouts.app')

@section('content')
    <!-- Paw Decorations -->
    <div class="paw-shape paw-1"></div>
    <div class="paw-shape paw-2"></div>
    <div class="paw-shape paw-3"></div>

    <!-- Floating Bubbles -->
    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; bottom: 15%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; left: 15%; animation-delay: 2s;"></div>

    {{-- Hero Section --}}
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
                    <x-ui.button text="Get Started" type="primary" size="lg" />
                    <x-ui.button text="Learn More" type="white" size="lg" />
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

    {{-- Features Section --}}
    <section id="features" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                Amazing Features
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Everything you need to keep your pet happy, healthy, and well-cared for
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <x-cards.feature-card
                icon="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"
                title="Smart Reminders"
                description="Never miss feeding time, grooming, or vet appointments with intelligent notifications" />

            <x-cards.feature-card
                icon="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"
                title="Pet Profiles"
                description="Manage multiple pets with detailed profiles, health records, and growth tracking" />

            <x-cards.feature-card
                icon="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"
                title="Health Tracking"
                description="Monitor weight, height, and overall health with interactive charts and insights" />
        </div>
    </section>

    {{-- How It Works Section --}}
    <section id="about" class="relative z-10 max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                How It Works
            </h2>
            <p class="text-lg text-gray-600">
                Get started in just 3 simple steps
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-12">
            <x-cards.step-item number="1" bgColor="bg-[#68C4CF]" title="Download App"
                description="Get Paw Time from App Store or Google Play" />

            <x-cards.step-item number="2" bgColor="bg-[#FF8C42]" title="Create Profile"
                description="Add your pet's information and preferences" />

            <x-cards.step-item number="3" bgColor="bg-[#68C4CF]" title="Start Caring"
                description="Set reminders and track your pet's wellness" />
        </div>
    </section>

    {{-- CTA Section --}}
    <section id="contact" class="relative z-10 max-w-5xl mx-auto px-6 py-20">
        <div class="cta-section">
            <h2>Ready to Start?</h2>
            <p>Join thousands of pet owners who trust Paw Time to keep their furry friends happy and healthy</p>
            <div class="flex flex-wrap justify-center gap-4">
                <x-ui.button text="App Store" type="primary" size="lg" class="cta-custom-btn" />
                <x-ui.button text="Google Play" type="primary" size="lg" class="cta-custom-btn" />
            </div>
        </div>
    </section>
@endsection
