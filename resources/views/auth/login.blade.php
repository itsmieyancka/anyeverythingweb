<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500">
        <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-8 rounded-2xl shadow-2xl border border-white/30">
            <h2 class="text-4xl font-extrabold text-center text-white drop-shadow mb-6">Welcome Back</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="('Email')" class="text-white" />
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
                    <x-input-label for="password" :value="('Password')" class="text-white" />
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
                <div class="block mt-4 text-white">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500"
                            name="remember"
                        />
                        <span class="ml-2 text-sm">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button & Links -->
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-white hover:text-gray-200" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                    <x-primary-button class="bg-purple-600 hover:bg-purple-700 text-white">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
