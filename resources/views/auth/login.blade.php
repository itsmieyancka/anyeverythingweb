<x-guest-layout>
    <div class="w-full max-w-7xl mx-auto px-4">
        <div class="flex min-h-screen w-full">
            <!-- Left Side: Your image inside DaisyUI card -->
            <div class="w-1/2 hidden lg:flex items-center justify-center p-4">
                <div class="card card-side bg-base-100 shadow-sm w-full max-w-md">
                    <figure>
                        <img src="{{ asset('images/login_promo.jpg') }}" alt="Login Promo" class="w-full h-full object-cover" />
                    </figure>
                    <!-- Optional: You can add a card body if you want text beside the image -->
                </div>
            </div>

            <!-- Right Side: Login form -->
            <div class="w-1/2 flex items-center justify-center p-12 bg-white shadow-inner">
                <div class="w-full max-w-md">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Welcome Back</h2>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="('Email')" />
                            <x-text-input
                                id="email"
                                class="block mt-1 w-full"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="('Password')" />
                            <x-text-input
                                id="password"
                                class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                            />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    name="remember"
                                />
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <!-- Submit Button and links -->
                        <div class="flex items-center justify-between mt-6">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <x-primary-button class="ml-3">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
