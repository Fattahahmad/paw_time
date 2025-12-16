<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paw Time - Login & Register</title>
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
            z-index: 0;
        }

        .paw-1 {
            width: 300px;
            height: 300px;
            background: #FFD4B2;
            top: -100px;
            left: -100px;
        }

        .paw-2 {
            width: 250px;
            height: 250px;
            background: #FFE8D6;
            bottom: -80px;
            right: -80px;
        }

        .paw-3 {
            width: 200px;
            height: 200px;
            background: #D4F1F4;
            top: 50%;
            right: -50px;
        }

        .btn-primary {
            background: #68C4CF;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #5AB0BB;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(104, 196, 207, 0.3);
        }

        .btn-google {
            background: white;
            border: 2px solid #E5E7EB;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            border-color: #68C4CF;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .input-field {
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #68C4CF;
            box-shadow: 0 0 0 3px rgba(104, 196, 207, 0.1);
        }

        .auth-card {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .bubble {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: bubble-float 4s ease-in-out infinite;
            z-index: 0;
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

        .paw-print {
            width: 50px;
            height: 50px;
            filter: drop-shadow(0 4px 6px rgba(255, 165, 0, 0.3));
        }

        .tab-btn {
            position: relative;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #68C4CF;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #68C4CF;
            border-radius: 3px;
        }

        .show {
            display: block !important;
        }

        .hide {
            display: none !important;
        }

        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .password-toggle:hover {
            color: #68C4CF;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4">
    <div class="paw-shape paw-1"></div>
    <div class="paw-shape paw-2"></div>
    <div class="paw-shape paw-3"></div>

    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; top: 15%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; left: 15%; animation-delay: 2s;"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <svg class="paw-print" viewBox="0 0 24 24" fill="#FF8C42" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm8 0c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm-4 2c-1.84 0-3.56.5-5.03 1.37-.61.35-.97 1.02-.97 1.72V18c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2v-2.91c0-.7-.36-1.37-.97-1.72C15.56 12.5 13.84 12 12 12z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Paw Time</h1>
            <p class="text-gray-600 mt-2">Take Care Of Your Pet</p>
        </div>

        <div class="auth-card bg-white rounded-3xl shadow-2xl p-8">
            <div class="flex border-b border-gray-200 mb-8">
                <button onclick="switchTab('login')" id="loginTab"
                    class="tab-btn active flex-1 pb-4 text-lg font-semibold text-gray-500">
                    Sign In
                </button>
                <button onclick="switchTab('register')" id="registerTab"
                    class="tab-btn flex-1 pb-4 text-lg font-semibold text-gray-500">
                    Sign Up
                </button>
            </div>

            <div id="loginForm" class="show">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                <p class="text-gray-600 mb-6">Sign in to continue caring for your pet</p>

                <form class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                            </span>
                            <input type="email" placeholder="yourname@email.com"
                                class="input-field w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                            </span>
                            <input type="password" id="loginPassword" placeholder="••••••••"
                                class="input-field w-full pl-12 pr-12 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50">
                            <span
                                class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"
                                onclick="togglePassword('loginPassword', this)">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                class="w-4 h-4 text-[#68C4CF] border-gray-300 rounded focus:ring-[#68C4CF]">
                            <span class="ml-2 text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-[#68C4CF] font-semibold hover:text-[#5AB0BB]">Forgot Password?</a>
                    </div>

                    <button type="submit"
                        class="btn-primary w-full py-4 rounded-2xl text-white font-semibold text-lg shadow-lg">
                        Sign In
                    </button>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <button type="button"
                        class="btn-google w-full py-4 rounded-2xl font-semibold text-gray-700 shadow-md flex items-center justify-center space-x-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Login with Google</span>
                    </button>
                </form>
            </div>

            <div id="registerForm" class="hide">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600 mb-6">Join us to start caring for your pet</p>

                <form class="space-y-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </span>
                            <input type="text" placeholder="John Doe"
                                class="input-field w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                </svg>
                            </span>
                            <input type="email" placeholder="yourname@email.com"
                                class="input-field w-full pl-12 pr-4 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                            </span>
                            <input type="password" id="registerPassword" placeholder="••••••••"
                                class="input-field w-full pl-12 pr-12 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50">
                            <span
                                class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"
                                onclick="togglePassword('registerPassword', this)">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                            </span>
                            <input type="password" id="confirmPassword" placeholder="••••••••"
                                class="input-field w-full pl-12 pr-12 py-3.5 border-2 border-gray-200 rounded-2xl text-gray-700 bg-gray-50">
                            <span
                                class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400"
                                onclick="togglePassword('confirmPassword', this)">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="text-sm">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox"
                                class="w-4 h-4 mt-0.5 text-[#68C4CF] border-gray-300 rounded focus:ring-[#68C4CF]">
                            <span class="ml-2 text-gray-600">
                                I agree to the
                                <a href="#" class="text-[#68C4CF] font-semibold hover:text-[#5AB0BB]">Terms &
                                    Conditions</a>
                                and
                                <a href="#" class="text-[#68C4CF] font-semibold hover:text-[#5AB0BB]">Privacy
                                    Policy</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit"
                        class="btn-primary w-full py-4 rounded-2xl text-white font-semibold text-lg shadow-lg">
                        Create Account
                    </button>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or sign up with</span>
                        </div>
                    </div>

                    <button type="button"
                        class="btn-google w-full py-4 rounded-2xl font-semibold text-gray-700 shadow-md flex items-center justify-center space-x-3">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        <span>Sign up with Google</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ url('/') }}"
                class="text-gray-600 hover:text-[#68C4CF] font-medium inline-flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Home</span>
            </a>
        </div>
    </div>

    <script>
        // Switch between Login and Register
        function switchTab(tab) {
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            if (tab === 'login') {
                loginTab.classList.add('active');
                registerTab.classList.remove('active');
                loginForm.classList.remove('hide');
                loginForm.classList.add('show');
                registerForm.classList.remove('show');
                registerForm.classList.add('hide');
            } else {
                registerTab.classList.add('active');
                loginTab.classList.remove('active');
                registerForm.classList.remove('hide');
                registerForm.classList.add('show');
                loginForm.classList.remove('show');
                loginForm.classList.add('hide');
            }
        }

        // Toggle Password Visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';

            // Change icon
            if (isPassword) {
                // Show "Eye Off" icon (because we just revealed the password)
                icon.innerHTML = `
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" />
                    </svg>
                `;
            } else {
                // Show "Eye" icon (because we just hid the password)
                icon.innerHTML = `
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                    </svg>
                `;
            }
        }
    </script>
</body>

</html>
