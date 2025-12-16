    <footer class="relative z-10 bg-white/50 backdrop-blur-sm mt-20">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF8C42" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm8 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c-1.84 0-3.56.5-5.03 1.37-.61.35-.97 1.02-.97 1.72V18c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-2.91c0-.7-.36-1.37-.97-1.72C15.56 12.5 13.84 12 12 12z" />
                        </svg>
                        <span class="text-xl font-bold text-gray-800">Paw Time</span>
                    </div>
                    <p class="text-gray-600">Take care of your beloved pet with love and technology</p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#" class="hover:text-[#68C4CF]">Features</a></li>
                        <li><a href="#" class="hover:text-[#68C4CF]">Pricing</a></li>
                        <li><a href="#" class="hover:text-[#68C4CF]">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-4">Company</h4>
                    <ul class="space-y-2 text-gray-600">
                        <li><a href="#" class="hover:text-[#68C4CF]">About Us</a></li>
                        <li><a href="#" class="hover:text-[#68C4CF]">Contact</a></li>
                        <li><a href="#" class="hover:text-[#68C4CF]">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-4">Follow Us</h4>
                    <div class="flex space-x-3">
                        @include('components.social-icon', [
                            'icon' => 'facebook',
                            'url' => '#',
                            'color' => 'teal',
                        ])
                        @include('components.social-icon', [
                            'icon' => 'twitter',
                            'url' => '#',
                            'color' => 'orange',
                        ])
                        @include('components.social-icon', [
                            'icon' => 'youtube',
                            'url' => '#',
                            'color' => 'teal',
                        ])
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-300 mt-8 pt-8 text-center text-gray-600">
                <p>&copy; 2025 Paw Time. All rights reserved. Made with ❤️ for pets</p>
            </div>
        </div>
    </footer>
