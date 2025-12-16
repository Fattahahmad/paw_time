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
        @include('components.step-item', [
            'number' => '1',
            'bgColor' => 'bg-[#68C4CF]',
            'title' => 'Download App',
            'description' => 'Get Paw Time from App Store or Google Play',
        ])

        @include('components.step-item', [
            'number' => '2',
            'bgColor' => 'bg-[#FF8C42]',
            'title' => 'Create Profile',
            'description' => 'Add your pet\'s information and preferences',
        ])

        @include('components.step-item', [
            'number' => '3',
            'bgColor' => 'bg-[#68C4CF]',
            'title' => 'Start Caring',
            'description' => 'Set reminders and track your pet\'s wellness',
        ])
    </div>
</section>
