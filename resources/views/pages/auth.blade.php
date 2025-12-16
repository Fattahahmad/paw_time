@extends('layouts.auth')

@section('content')
    <!-- Paw Decorations -->
    <div class="paw-shape paw-1"></div>
    <div class="paw-shape paw-2"></div>
    <div class="paw-shape paw-3"></div>

    <!-- Floating Bubbles -->
    <div class="bubble" style="width: 40px; height: 40px; bottom: 10%; left: 5%; animation-delay: 0s;"></div>
    <div class="bubble" style="width: 60px; height: 60px; top: 15%; right: 10%; animation-delay: 1s;"></div>
    <div class="bubble" style="width: 30px; height: 30px; top: 30%; left: 15%; animation-delay: 2s;"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                @include('components.icon', [
                    'name' => 'paw',
                    'size' => 'w-12 h-12',
                    'color' => '#FF8C42',
                ])
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Paw Time</h1>
            <p class="text-gray-600 mt-2">Take Care Of Your Pet</p>
        </div>

        <!-- Auth Card -->
        <div class="auth-card bg-white rounded-3xl shadow-2xl p-8">
            <!-- Tabs -->
            @include('components.tab-switch', [
                'tabs' => [['id' => 'login', 'label' => 'Sign In'], ['id' => 'register', 'label' => 'Sign Up']],
                'activeTab' => 'login',
            ])

            <!-- Login Form -->
            <div id="loginForm" class="show">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                <p class="text-gray-600 mb-6">Sign in to continue caring for your pet</p>

                <form class="space-y-5">
                    @include('components.form-input', [
                        'label' => 'Email',
                        'type' => 'email',
                        'placeholder' => 'yourname@email.com',
                        'icon' => 'email',
                        'id' => 'loginEmail',
                        'required' => true,
                    ])

                    @include('components.form-input', [
                        'label' => 'Password',
                        'type' => 'password',
                        'placeholder' => '••••••••',
                        'icon' => 'password',
                        'id' => 'loginPassword',
                        'passwordToggleId' => true,
                        'required' => true,
                    ])

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                class="w-4 h-4 text-[#68C4CF] border-gray-300 rounded focus:ring-[#68C4CF]">
                            <span class="ml-2 text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="text-[#68C4CF] font-semibold hover:text-[#5AB0BB]">Forgot Password?</a>
                    </div>

                    @include('components.button', [
                        'text' => 'Sign In',
                        'type' => 'primary',
                        'size' => 'md',
                        'class' => 'w-full py-4',
                    ])

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <!-- Google Button -->
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

            <!-- Register Form -->
            <div id="registerForm" class="hide">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600 mb-6">Join us to start caring for your pet</p>

                <form class="space-y-5">
                    @include('components.form-input', [
                        'label' => 'Full Name',
                        'type' => 'text',
                        'placeholder' => 'John Doe',
                        'icon' => 'user',
                        'id' => 'fullName',
                        'required' => true,
                    ])

                    @include('components.form-input', [
                        'label' => 'Email',
                        'type' => 'email',
                        'placeholder' => 'yourname@email.com',
                        'icon' => 'email',
                        'id' => 'registerEmail',
                        'required' => true,
                    ])

                    @include('components.form-input', [
                        'label' => 'Password',
                        'type' => 'password',
                        'placeholder' => '••••••••',
                        'icon' => 'password',
                        'id' => 'registerPassword',
                        'passwordToggleId' => true,
                        'required' => true,
                    ])

                    @include('components.form-input', [
                        'label' => 'Confirm Password',
                        'type' => 'password',
                        'placeholder' => '••••••••',
                        'icon' => 'password',
                        'id' => 'confirmPassword',
                        'passwordToggleId' => true,
                        'required' => true,
                    ])

                    <div class="flex items-center">
                        <input type="checkbox" class="w-4 h-4 text-[#68C4CF] border-gray-300 rounded focus:ring-[#68C4CF]">
                        <span class="ml-2 text-sm text-gray-600">I agree to the <a href="#"
                                class="text-[#68C4CF] font-semibold hover:text-[#5AB0BB]">Terms & Conditions</a></span>
                    </div>

                    @include('components.button', [
                        'text' => 'Create Account',
                        'type' => 'primary',
                        'size' => 'md',
                        'class' => 'w-full py-4',
                    ])

                    <!-- Google Button -->
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
                        <span>Sign Up with Google</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function switchTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');

            if (tab === 'login') {
                loginForm.classList.add('show');
                loginForm.classList.remove('hide');
                registerForm.classList.add('hide');
                registerForm.classList.remove('show');
                loginTab.classList.add('active');
                registerTab.classList.remove('active');
            } else {
                registerForm.classList.add('show');
                registerForm.classList.remove('hide');
                loginForm.classList.add('hide');
                loginForm.classList.remove('show');
                registerTab.classList.add('active');
                loginTab.classList.remove('active');
            }
        }

        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                iconElement.classList.add('text-[#68C4CF]');
            } else {
                input.type = 'password';
                iconElement.classList.remove('text-[#68C4CF]');
            }
        }
    </script>
@endsection
