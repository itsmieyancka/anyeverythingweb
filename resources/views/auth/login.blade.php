<x-guest-layout>
    <div class="w-full max-w-7xl mx-auto px-4 py-8">
        <!-- Flex container to position image on the left and form on the far right -->
        <div class="flex min-h-screen w-full justify-between items-center">
            <!-- Left: Image carousel or large image -->
            <div class="w-3/4 flex items-center justify-center p-4">
                <div class="carousel carousel-vertical rounded-box h-96 w-full max-w-4xl">
                    <!-- Image slide -->
                    <div class="carousel-item h-full">
                        <img src="{{ asset('images/login_promo.jpg') }}" class="w-full h-full object-cover" />
                    </div>
                </div>
            </div>

            <!-- Right: Login form, now not compact -->
            <div class="w-1/4 flex items-center justify-start p-4">
                <!-- Remove max-w-sm and make form container wider -->
                <div class="w-full bg-white p-8 rounded-lg shadow-lg">
                    <h2 class="text-3xl font-bold text-gray-800 mb-6">Welcome Back</h2>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
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

                        <!-- Submit Button & Links -->
                        <div class="flex items-center justify-end mt-6">
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
