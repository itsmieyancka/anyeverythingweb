@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">

        {{-- Breadcrumb with Continue Shopping --}}
        <nav class="mb-6" aria-label="breadcrumb">
            <ol class="flex text-sm text-gray-500 space-x-2">
                <li>
                    <a href="{{ route('home') }}" class="hover:underline text-blue-600">Home</a>
                    <span>/</span>
                </li>
                <li class="text-gray-700">Checkout</li>
            </ol>
        </nav>

        <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
            <h2 class="text-2xl font-bold mb-6">Checkout</h2>

            {{-- Order Summary --}}
            <div class="mb-6 border-b pb-4">
                <h3 class="font-bold mb-2">Your Order</h3>
                @foreach($cart as $item)
                    <div class="flex justify-between py-2">
                        <div>
                            {{ $item['quantity'] }} × {{ $item['product']['name'] ?? 'No name' }}
                            @if(!empty($item['options']))
                                <div class="text-sm text-gray-500">{{ $item['options'] }}</div>
                            @endif
                        </div>
                        <div>R{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                    </div>
                @endforeach
            </div>

            {{-- Cost Summary --}}
            <table class="table-auto w-full text-sm mb-6">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">R<span id="subtotal">{{ number_format($subtotal, 2) }}</span></td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td class="text-right">R<span id="shipping-cost">{{ number_format($shipping, 2) }}</span></td>
                </tr>
                <tr class="font-bold border-t">
                    <td class="pt-2">Total</td>
                    <td class="text-right pt-2">R<span id="total-cost">{{ number_format($total, 2) }}</span></td>
                </tr>
            </table>

            <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
                @csrf

                {{-- Shipping Details --}}
                <div class="mb-6">
                    <h3 class="font-bold mb-4">Shipping Details</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="first_name" class="block font-medium">First Name</label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name', auth()->user()->first_name ?? '') }}" class="input input-bordered w-full mt-1" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block font-medium">Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address ?? '') }}" class="input input-bordered w-full mt-1" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="city" class="block font-medium">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city', auth()->user()->city ?? '') }}" class="input input-bordered w-full mt-1" required>
                        </div>
                        <div>
                            <label for="postal_code" class="block font-medium">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}" class="input input-bordered w-full mt-1" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block font-medium">Phone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" class="input input-bordered w-full mt-1" required>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="mb-6">
                    <h3 class="font-bold mb-2">Shipping Method</h3>
                    <div class="space-y-2">
                        <label class="flex items-center p-3 border rounded cursor-pointer">
                            <input type="radio" name="shipping_method" value="standard" class="radio radio-primary" checked data-price="50">
                            <div class="ml-3 w-full">
                                <div class="flex justify-between">
                                    <span>Standard Shipping</span>
                                    <span>R50.00</span>
                                </div>
                                <p class="text-sm text-gray-500">3-5 business days</p>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border rounded cursor-pointer">
                            <input type="radio" name="shipping_method" value="express" class="radio radio-primary" data-price="100">
                            <div class="ml-3 w-full">
                                <div class="flex justify-between">
                                    <span>Express Shipping</span>
                                    <span>R100.00</span>
                                </div>
                                <p class="text-sm text-gray-500">1-2 business days</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="mb-6">
                    <h3 class="font-bold mb-4">Payment</h3>
                    <div class="space-y-2 mb-4">
                        <label class="flex items-center p-3 border rounded cursor-pointer">
                            <input type="radio" name="payment_method" value="credit_card" class="radio radio-primary" checked>
                            <span class="ml-3">Credit Card</span>
                        </label>
                        <label class="flex items-center p-3 border rounded cursor-pointer">
                            <input type="radio" name="payment_method" value="paypal" class="radio radio-primary">
                            <span class="ml-3">PayPal</span>
                        </label>
                    </div>

                    <div id="credit-card-form">
                        <div class="mb-4">
                            <label for="card-holder" class="block font-medium">Cardholder Name</label>
                            <input type="text" id="card-holder" name="card_holder" class="input input-bordered w-full mt-1" required>
                        </div>

                        <div class="mb-4">
                            <label for="card-number" class="block font-medium">Card Number</label>
                            <input type="text" id="card-number" name="card_number" class="input input-bordered w-full mt-1" placeholder="4242 4242 4242 4242" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="card-expiry" class="block font-medium">Expiry Date</label>
                                <input type="text" id="card-expiry" name="card_expiry" class="input input-bordered w-full mt-1" placeholder="MM/YY" required>
                            </div>
                            <div>
                                <label for="card-cvc" class="block font-medium">CVC</label>
                                <input type="text" id="card-cvc" name="card_cvc" class="input input-bordered w-full mt-1" placeholder="123" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="checkout-errors" class="text-red-600 mb-4"></div>

                <button type="submit" class="btn btn-primary w-full py-3 text-lg">
                    Pay R<span id="pay-amount">{{ number_format($total, 2) }}</span>
                </button>
            </form>
        </div>
        @endsection

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Shipping method change handler
                    document.querySelectorAll('input[name="shipping_method"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            if(this.checked) {
                                const shippingCost = parseFloat(this.dataset.price);
                                const subtotal = parseFloat({{ $subtotal }});
                                const total = subtotal + shippingCost;

                                document.getElementById('shipping-cost').textContent = shippingCost.toFixed(2);
                                document.getElementById('total-cost').textContent = total.toFixed(2);
                                document.getElementById('pay-amount').textContent = total.toFixed(2);
                            }
                        });
                    });

                    // Toggle credit card fields
                    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            document.getElementById('credit-card-form').style.display =
                                this.value === 'credit_card' ? 'block' : 'none';
                        });
                    });

                    // Format card number
                    document.getElementById('card-number').addEventListener('input', function(e) {
                        this.value = this.value.replace(/\s/g, '').replace(/(\d{4})/g, '$1 ').trim();
                    });

                    // Format expiry
                    document.getElementById('card-expiry').addEventListener('input', function(e) {
                        this.value = this.value.replace(/\D/g, '').replace(/(\d{2})(\d{0,2})/, '$1/$2');
                    });

                    // AJAX form submit for immediate redirect
                    const form = document.getElementById('checkout-form');
                    const errorsDiv = document.getElementById('checkout-errors');
                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        errorsDiv.textContent = '';

                        const formData = new FormData(form);

                        try {
                            const response = await fetch(form.action, {
                                method: 'POST',
                                headers: { 'Accept': 'application/json' },
                                body: formData
                            });
                            const result = await response.json();
                            if(result.success && result.redirect) {
                                window.location.href = result.redirect;
                            } else if(result.error) {
                                errorsDiv.textContent = result.error;
                            } else {
                                errorsDiv.textContent = 'Unexpected error occurred.';
                            }
                        } catch (err) {
                            errorsDiv.textContent = 'Network or server error. Please try again.';
                        }
                    });
                });
            </script>
    @endpush
