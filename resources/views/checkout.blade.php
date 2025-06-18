@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow mt-10">
        <h1 class="text-2xl font-semibold mb-6">Checkout</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="mb-4">
                <label for="name" class="block font-medium mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="input input-bordered w-full" />
            </div>

            <div class="mb-4">
                <label for="address" class="block font-medium mb-1">Shipping Address</label>
                <textarea name="address" id="address" rows="3" required
                          class="textarea textarea-bordered w-full">{{ old('address') }}</textarea>
            </div>

            <div class="mb-6">
                <span class="block font-medium mb-2">Shipping Method</span>
                <label class="inline-flex items-center mr-6">
                    <input type="radio" name="shipping_method" value="standard"
                           {{ old('shipping_method', 'standard') == 'standard' ? 'checked' : '' }} required />
                    <span class="ml-2">Standard Shipping (5-7 days) - R40</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="shipping_method" value="express"
                        {{ old('shipping_method') == 'express' ? 'checked' : '' }} />
                    <span class="ml-2">Express Shipping (1-2 days) - R80</span>
                </label>
            </div>

            <h2 class="text-xl font-semibold mb-4">Payment Details</h2>

            <div class="mb-4">
                <label for="card_number" class="block font-medium mb-1">Card Number</label>
                <input type="text" name="card_number" id="card_number" value="{{ old('card_number') }}"
                       placeholder="4242 4242 4242 4242" required class="input input-bordered w-full" />
            </div>

            <div class="mb-4 flex space-x-4">
                <div class="flex-1">
                    <label for="expiry" class="block font-medium mb-1">Expiry (MM/YY)</label>
                    <input type="text" name="expiry" id="expiry" value="{{ old('expiry') }}" placeholder="11/35"
                           required class="input input-bordered w-full" />
                </div>
                <div class="flex-1">
                    <label for="cvc" class="block font-medium mb-1">CVC</label>
                    <input type="text" name="cvc" id="cvc" value="{{ old('cvc') }}" placeholder="123"
                           required class="input input-bordered w-full" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full py-3 text-lg" id="payButton">
                Pay
            </button>
        </form>

        {{-- Loading Spinner --}}
        <div id="loading-spinner" class="mt-6 hidden text-center">
            <span class="loading loading-spinner loading-lg"></span>
            <p class="mt-2 text-gray-600">Processing your payment...</p>
        </div>

        {{-- Success Message --}}
        <div id="success-message" class="mt-6 hidden">
            <div role="alert" class="alert alert-success shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current"
                     fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Your payment is successful!</span>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            e.preventDefault();

            // Show loading spinner
            document.getElementById('loading-spinner').classList.remove('hidden');
            document.getElementById('payButton').disabled = true;

            // Simulate 1.5s delay, then show success, then submit after 2s
            setTimeout(() => {
                document.getElementById('loading-spinner').classList.add('hidden');
                document.getElementById('success-message').classList.remove('hidden');

                setTimeout(() => {
                    e.target.submit();
                }, 2000);
            }, 1500);
        });
    </script>
@endpush

