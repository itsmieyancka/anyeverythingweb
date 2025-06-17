<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center px-4 py-12 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        {{-- Container with space for logo --}}
        <div class="flex w-full max-w-5xl space-x-8">

            {{-- Left side: Image (about 50% width) --}}
            <div class="flex-1 rounded-lg shadow-lg overflow-hidden relative">
                {{-- Optional logo space above image --}}
                <div class="absolute top-4 left-4 z-10">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12 w-auto" />
                </div>
                <img src="{{ asset('images/login_promo.jpg') }}" alt="Login Promo" class="w-full h-full object-cover" />
            </div>

            {{-- Right side: Login form (about 50% width) --}}
            <div class="flex-1 bg-white rounded-lg shadow-lg p-10 flex flex-col justify-center">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block mt-1 w-full"
                                      type="password"
                                      name="password"
                                      required autocomplete="current-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

