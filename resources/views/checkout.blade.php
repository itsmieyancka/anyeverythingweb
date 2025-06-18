@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white rounded shadow mt-10">
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Checkout Form --}}
            <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block font-medium mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input input-bordered w-full" />
                </div>

                <div class="mb-4">
                    <label for="address" class="block font-medium mb-1">Shipping Address</label>
                    <textarea name="address" id="address" rows="3" required class="textarea textarea-bordered w-full">{{ old('address') }}</textarea>
                </div>

                <div class="mb-6">
                    <span class="block font-medium mb-2">Shipping Method</span>
                    <label class="inline-flex items-center mr-6">
                        <input type="radio" name="shipping_method" value="standard" {{ old('shipping_method', 'standard') == 'standard' ? 'checked' : '' }} required />
                        <span class="ml-2">Standard Shipping (5-7 days) - R40</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="shipping_method" value="express" {{ old('shipping_method') == 'express' ? 'checked' : '' }} />
                        <span class="ml-2">Express Shipping (1-2 days) - R80</span>
                    </label>
                </div>

                <h2 class="text-xl font-semibold mb-4">Payment Details</h2>

                <div class="mb-4">
                    <label for="card_number" class="block font-medium mb-1">Card Number</label>
                    <input type="text" name="card_number" id="card_number" value="{{ old('card_number') }}" placeholder="4242 4242 4242 4242" required class="input input-bordered w-full" />
                </div>

                <div class="mb-4 flex space-x-4">
                    <div class="flex-1">
                        <label for="expiry" class="block font-medium mb-1">Expiry (MM/YY)</label>
                        <input type="text" name="expiry" id="expiry" value="{{ old('expiry') }}" placeholder="11/35" required class="input input-bordered w-full" />
                    </div>
                    <div class="flex-1">
                        <label for="cvc" class="block font-medium mb-1">CVC</label>
                        <input type="text" name="cvc" id="cvc" value="{{ old('cvc') }}" placeholder="123" required class="input input-bordered w-full" />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full py-3 text-lg" id="payButton">
                    Pay
                </button>
            </form>

            {{-- Order Summary --}}
            <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg shadow-sm">
                <h2 class="text-lg font-semibold mb-4">Order Summary</h2>

                <ul class="divide-y divide-gray-200 mb-4">
                    @foreach ($cart as $item)
                        <li class="py-2 flex justify-between items-start">
                            <div>
                                <p class="font-medium">{{ $item['name'] }}</p>
                                <p class="text-sm text-gray-500">x{{ $item['quantity'] }}</p>
                            </div>
                            <p class="text-right">R{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </li>
                    @endforeach
                </ul>

                <div class="border-t pt-4 text-sm space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>R{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>R{{ number_format($shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg mt-2">
                        <span>Total</span>
                        <span>R{{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
