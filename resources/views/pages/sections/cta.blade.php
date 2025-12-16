<section id="contact" class="relative z-10 max-w-5xl mx-auto px-6 py-20">
    <div class="cta-section">
        <h2>Ready to Start?</h2>
        <p>Join thousands of pet owners who trust Paw Time to keep their furry friends happy and healthy</p>
        <div class="flex flex-wrap justify-center gap-4">
            @include('components.button', [
                'text' => 'App Store',
                'type' => 'primary',
                'size' => 'lg',
                'class' => 'cta-custom-btn',
            ])
            @include('components.button', [
                'text' => 'Google Play',
                'type' => 'primary',
                'size' => 'lg',
                'class' => 'cta-custom-btn',
            ])
        </div>
    </div>
</section>
