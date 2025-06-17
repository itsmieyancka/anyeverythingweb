<x-guest-layout>
    <div class="flex min-h-screen">
        <!-- Left Side: Login Form (50%) -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-4 sm:p-8 md:p-16 lg:p-20 bg-white">
            <div class="w-full max-w-lg">  <!-- Increased max width -->
                <!-- Header Section -->
                <div class="text-center mb-10">  <!-- Added more bottom margin -->
                    <h2 class="text-4xl font-bold text-gray-900">Welcome back</h2>  <!-- Larger text -->
                    <p class="mt-4 text-lg text-gray-600">  <!-- Larger text -->
                        Or
                        <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                            create a new account
                        </a>
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6" :status="session('status')" />  <!-- More spacing -->

                <form method="POST" action="{{ route('login') }}" class="space-y-8">  <!-- Increased vertical spacing -->
                    @csrf

                    <div class="space-y-6">  <!-- More spacing between fields -->
                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-lg"/>  <!-- Larger label -->
                            <x-text-input id="email"
                                          class="block mt-2 w-full p-3 text-lg border-gray-300"  <!-- Larger input -->
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Enter your email"/>
                            <x-input-error :messages="$errors->get('email')" class="mt-3" />  <!-- More spacing -->
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-lg"/>  <!-- Larger label -->
                            <x-text-input id="password"
                                          class="block mt-2 w-full p-3 text-lg border-gray-300"  <!-- Larger input -->
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"/>
                            <x-input-error :messages="$errors->get('password')" class="mt-3" />  <!-- More spacing -->
                        </div>
                    </div>

                    <!-- Remember Me + Forgot Password -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me"
                                   type="checkbox"
                                   class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"  <!-- Larger checkbox -->
                            name="remember">
                            <label for="remember_me" class="ml-3 block text-base text-gray-900">  <!-- Larger text -->
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-base">  <!-- Larger text -->
                                <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                    {{ __('Forgot password?') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-10">  <!-- More top margin -->
                        <x-primary-button class="w-full justify-center py-3 px-4 text-lg">  <!-- Larger button -->
                            {{ __('Sign in') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- Social Login Divider -->
                <div class="mt-12">  <!-- More spacing -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="px-4 bg-white text-gray-500 text-lg">  <!-- Larger text -->
                                Or continue with
                            </span>
                        </div>
                    </div>

                    <!-- Social Login Buttons -->
                    <div class="mt-8 grid grid-cols-2 gap-4">  <!-- More spacing -->
                        <div>
                            <a href="#" class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-lg font-medium text-gray-500 hover:bg-gray-50">  <!-- Larger button -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" />
                                </svg>
                            </a>
                        </div>
                        <div>
                            <a href="#" class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-lg font-medium text-gray-500 hover:bg-gray-50">  <!-- Larger button -->
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Image (50%) -->
        <div class="hidden md:block md:w-1/2 bg-gray-100">
            <img src="{{ asset('images/login_promo.jpg') }}" alt="Login Promo"
                 class="w-full h-full object-cover">  <!-- Simple image without overlay -->
        </div>
    </div>
</x-guest-layout>
