<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center mb-6">Create an Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Register as Vendor -->
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="register_as_vendor" id="register_as_vendor" value="1" class="form-checkbox text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <span class="ml-2 text-sm text-gray-700">Register as Vendor</span>
                </label>
            </div>

            <!-- Business Name (conditional) -->
            <div id="vendor-fields" class="mb-4 hidden">
                <x-input-label for="business_name" :value="__('Business Name')" />
                <x-text-input id="business_name" class="mt-1 block w-full" type="text" name="business_name" :value="old('business_name')" />
                <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-between mt-6">
                <a class="text-sm text-indigo-600 hover:underline" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button>
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    <!-- Toggle Vendor Fields -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('register_as_vendor');
            const vendorFields = document.getElementById('vendor-fields');

            function toggleVendorFields() {
                vendorFields.classList.toggle('hidden', !checkbox.checked);
            }

            checkbox.addEventListener('change', toggleVendorFields);

            // Ensure visibility on validation error
            if (checkbox.checked || '{{ old('business_name') }}') {
                checkbox.checked = true;
                vendorFields.classList.remove('hidden');
            }
        });
    </script>
</x-guest-layout>
