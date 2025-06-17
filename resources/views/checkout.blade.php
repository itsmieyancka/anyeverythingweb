@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
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

        <div class="flex flex-col md:flex-row gap-8">
            {{-- Left: Billing Form --}}
            <div class="w-full md:w-2/3">
                <h2 class="text-xl font-semibold mb-4">Shipping & Payment</h2>

                <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" id="email" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium">Phone</label>
                        <input type="text" name="phone" id="phone" class="w-full mt-1 p-2 border rounded-md" required>
                    </div>

                    {{-- Address --}}
                    <div class="mb-4">
                        <label for="shipping_address" class="block text-sm font-medium">Shipping Address</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" class="w-full mt-1 p-2 border rounded-md" required></textarea>
                    </div>

                    {{-- Unit/Apartment --}}
                    <div class="mb-4">
                        <label for="shipping_unit" class="block text-sm font-medium">Unit / Apartment (optional)</label>
                        <input type="text" name="shipping_unit" id="shipping_unit" class="w-full mt-1 p-2 border rounded-md">
                    </div>

                    {{-- Shipping Method --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Shipping Method</label>
                        <select name="shipping_method" id="shipping_method" class="w-full p-2 border rounded-md">
                            <option value="standard">Standard (R50)</option>
                            <option value="express">Express (R100)</option>
                        </select>
                    </div>

                    {{-- Card Element --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-1">Payment</label>
                        <div id="card-element" class="p-3 border rounded-md bg-white"></div>
                        <div id="card-errors" class="text-red-500 mt-2 text-sm"></div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="submit-button" class="w-full py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                        Pay Now
                    </button>

                    {{-- Feedback --}}
                    <div id="payment-feedback" class="mt-4 text-sm"></div>
                </form>
            </div>

            {{-- Right: Order Summary --}}
            <div class="w-full md:w-1/3 bg-gray-50 p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold mb-4">Order Summary</h3>

                @php
                    $cart = session('cart', []);
                    $subtotal = 0;
                    foreach ($cart as $item) {
                        $subtotal += $item['price'] * $item['quantity'];
                    }
                @endphp

                @foreach ($cart as $item)
                    <div class="mb-3 border-b pb-2">
                        <div class="flex justify-between text-sm">
                            {{-- Show product name --}}
                            <span>{{ $item['product']->name ?? 'Unknown product' }} x {{ $item['quantity'] }}</span>

                            {{-- Show total price for this line item --}}
                            <span>R{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>

                        {{-- Show variation options if any --}}
                        @if (!empty($item['variationSet']) && !empty($item['variationSet']->variationOptions) && $item['variationSet']->variationOptions->count())
                            <div class="text-xs text-gray-500 mt-1">
                                Variation:
                                {{ $item['variationSet']->variationOptions->pluck('value')->join(', ') }}
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="flex justify-between mt-2">
                    <span>Subtotal:</span>
                    <span>R{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between mt-1">
                    <span>Shipping:</span>
                    <span id="shipping-cost">R50.00</span>
                </div>
                <div class="flex justify-between font-semibold mt-3 border-t pt-2">
                    <span>Total:</span>
                    <span id="total-amount">R{{ number_format($subtotal + 50, 2) }}</span>
                </div>

                <a href="{{ route('home') }}" class="block text-sm text-blue-600 mt-4 hover:underline">
                    ← Continue Shopping
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stripe = Stripe('{{ config("services.stripe.key") }}');
            const elements = stripe.elements();
            const card = elements.create('card');
            card.mount('#card-element');

            const shippingSelect = document.getElementById('shipping_method');
            const shippingDisplay = document.getElementById('shipping-cost');
            const totalDisplay = document.getElementById('total-amount');
            const subtotal = {{ $subtotal }};

            function updateTotals() {
                const shipping = shippingSelect.value === 'express' ? 100 : 50;
                shippingDisplay.textContent = `R${shipping.toFixed(2)}`;
                totalDisplay.textContent = `R${(subtotal + shipping).toFixed(2)}`;
            }

            shippingSelect.addEventListener('change', updateTotals);
            updateTotals();

            const form = document.getElementById('checkout-form');
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                document.getElementById('submit-button').disabled = true;
                document.getElementById('payment-feedback').textContent = 'Processing payment...';
                document.getElementById('card-errors').textContent = '';

                const { paymentMethod, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: card,
                });

                if (error) {
                    document.getElementById('card-errors').textContent = error.message;
                    document.getElementById('submit-button').disabled = false;
                    document.getElementById('payment-feedback').textContent = '';
                    return;
                }

                // Append payment_method_id to form data
                const formData = new FormData(form);
                formData.append('payment_method_id', paymentMethod.id);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            document.getElementById('payment-feedback').textContent = data.error || 'Payment failed. Please try again.';
                            document.getElementById('submit-button').disabled = false;
                        }
                    })
                    .catch(() => {
                        document.getElementById('payment-feedback').textContent = 'Payment failed. Please try again.';
                        document.getElementById('submit-button').disabled = false;
                    });
            });
        });
    </script>
@endsection
