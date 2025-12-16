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
        @include('components.feature-card', [
            'icon' =>
                'M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z',
            'title' => 'Smart Reminders',
            'description' =>
                'Never miss feeding time, grooming, or vet appointments with intelligent notifications',
        ])

        @include('components.feature-card', [
            'icon' =>
                'M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z',
            'title' => 'Pet Profiles',
            'description' => 'Manage multiple pets with detailed profiles, health records, and growth tracking',
        ])

        @include('components.feature-card', [
            'icon' =>
                'M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z',
            'title' => 'Health Tracking',
            'description' => 'Monitor weight, height, and overall health with interactive charts and insights',
        ])
    </div>
</section>
