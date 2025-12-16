<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paw Time - Take Care Of Your Pet</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #FFD4B2 0%, #FFF5EE 50%, #E8F4F8 100%);
        }

        .paw-shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.6;
        }

        .paw-1 {
            width: 200px;
            height: 200px;
            background: #FFD4B2;
            top: -50px;
            left: -50px;
        }

        .paw-2 {
            width: 300px;
            height: 300px;
            background: #FFE8D6;
            bottom: -100px;
            left: 10%;
        }

        .paw-3 {
            width: 250px;
            height: 250px;
            background: #D4F1F4;
            top: 100px;
            right: -80px;
        }

        .btn-primary {
            background: #68C4CF;
            transition: all 0.3s ease;
        }

        .btn-white {
            background: #FFFFFF;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #5AB0BB;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(104, 196, 207, 0.3);
        }

        .btn-white:hover {
            background: #f8f5f2;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(104, 196, 207, 0.3);
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .hero-image {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: bubble-float 4s ease-in-out infinite;
        }

        @keyframes bubble-float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-30px);
            }
        }

        .feature-icon {
            background: linear-gradient(135deg, #FFD4B2 0%, #FFA07A 100%);
        }

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background: #68C4CF;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .paw-print {
            width: 60px;
            height: 60px;
            filter: drop-shadow(0 4px 6px rgba(255, 165, 0, 0.3));
        }
    </style>
</head>

<body class="gradient-bg">
    <div class="paw-shape paw-1"></div>
    <div class="paw-shape paw-2"></div>
    <div class="paw-shape paw-3"></div>

    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; bottom: 15%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; left: 15%; animation-delay: 2s;"></div>

    <nav class="relative z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between relative">

            <div class="flex items-center space-x-3 z-20">
                <svg class="paw-print" viewBox="0 0 24 24" fill="#FF8C42" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm8 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c-1.84 0-3.56.5-5.03 1.37-.61.35-.97 1.02-.97 1.72V18c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-2.91c0-.7-.36-1.37-.97-1.72C15.56 12.5 13.84 12 12 12z" />
                </svg>
                <span class="text-2xl font-bold text-gray-800">Paw Time</span>
            </div>

            <div class="hidden md:flex space-x-8 absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <a href="#home" class="nav-link text-gray-700 hover:text-[#68C4CF] font-medium">Home</a>
                <a href="#features" class="nav-link text-gray-700 hover:text-[#68C4CF] font-medium">Features</a>
                <a href="#about" class="nav-link text-gray-700 hover:text-[#68C4CF] font-medium">About</a>
                <a href="#contact" class="nav-link text-gray-700 hover:text-[#68C4CF] font-medium">Contact</a>
            </div>

            <div class="flex items-center space-x-4 z-20">
                <button class="btn-primary text-white px-6 py-2.5 rounded-full font-semibold">
                    Download
                </button>
                <button class="btn-white text-black px-6 py-2.5 rounded-full font-semibold">
                    Register
                </button>
            </div>

        </div>
    </nav>

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
                    <button class="btn-primary text-white px-8 py-4 rounded-full font-semibold text-lg shadow-lg">
                        Get Started
                    </button>
                    <button
                        class="btn-white text-gray-700 px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all">
                        Learn More
                    </button>
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
            <div class="card bg-white rounded-3xl p-8 shadow-lg">
                <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Smart Reminders</h3>
                <p class="text-gray-600">
                    Never miss feeding time, grooming, or vet appointments with intelligent notifications
                </p>
            </div>

            <div class="card bg-white rounded-3xl p-8 shadow-lg">
                <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Pet Profiles</h3>
                <p class="text-gray-600">
                    Manage multiple pets with detailed profiles, health records, and growth tracking
                </p>
            </div>

            <div class="card bg-white rounded-3xl p-8 shadow-lg">
                <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Health Tracking</h3>
                <p class="text-gray-600">
                    Monitor weight, height, and overall health with interactive charts and insights
                </p>
            </div>
        </div>
    </section>

    <section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                How It Works
            </h2>
            <p class="text-lg text-gray-600">
                Get started in just 3 simple steps
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-12">
            <div class="text-center">
                <div
                    class="bg-[#68C4CF] w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">1</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Download App</h3>
                <p class="text-gray-600">Get Paw Time from App Store or Google Play</p>
            </div>

            <div class="text-center">
                <div
                    class="bg-[#FF8C42] w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">2</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Create Profile</h3>
                <p class="text-gray-600">Add your pet's information and preferences</p>
            </div>

            <div class="text-center">
                <div
                    class="bg-[#68C4CF] w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <span class="text-3xl font-bold text-white">3</span>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Start Caring</h3>
                <p class="text-gray-600">Set reminders and track your pet's wellness</p>
            </div>
        </div>
    </section>

    <section class="relative z-10 max-w-5xl mx-auto px-6 py-20">
        <div class="bg-gradient-to-r from-[#68C4CF] to-[#5AB0BB] rounded-3xl p-12 shadow-2xl text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Start?
            </h2>
            <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                Join thousands of pet owners who trust Paw Time to keep their furry friends happy and healthy
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <button
                    class="bg-white text-[#68C4CF] px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all hover:scale-105">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z" />
                        </svg>
                        <span>App Store</span>
                    </div>
                </button>
                <button
                    class="bg-white text-[#68C4CF] px-8 py-4 rounded-full font-semibold text-lg shadow-lg hover:shadow-xl transition-all hover:scale-105">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z" />
                        </svg>
                        <span>Google Play</span>
                    </div>
                </button>
            </div>
        </div>
    </section>

    <footer class="relative z-10 bg-white/50 backdrop-blur-sm mt-20">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="#FF8C42"
                            xmlns="http://www.w3.org/2000/svg">
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
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-[#68C4CF] rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-[#FF8C42] rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-[#68C4CF] rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm4.441 16.892c-2.102.144-6.784.144-8.883 0C5.282 16.736 5.017 15.622 5 12c.017-3.629.285-4.736 2.558-4.892 2.099-.144 6.782-.144 8.883 0C18.718 7.264 18.982 8.378 19 12c-.018 3.629-.285 4.736-2.559 4.892zM10 9.658l4.917 2.338L10 14.342V9.658z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-300 mt-8 pt-8 text-center text-gray-600">
                <p>&copy; 2025 Paw Time. All rights reserved. Made with ❤️ for pets</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>

</html>
