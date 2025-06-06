@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>

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
            <tr class="font-bold">
                <td>Total</td>
                <td class="text-right">R<span id="total-cost">{{ number_format($total, 2) }}</span></td>
            </tr>
        </table>

        {{-- Shipping Method --}}
        <div class="mb-4">
            <label for="shipping" class="block font-medium">Select Shipping Method</label>
            <select name="shipping_method" id="shipping" class="select select-bordered w-full mt-1">
                <option value="standard" selected>Standard (R50)</option>
                <option value="express">Express (R100)</option>
            </select>
        </div>


        {{-- Stripe Payment Form --}}
        <form id="payment-form">
            @csrf

            <label for="name" class="block font-semibold">Full Name</label>
            <input id="name" name="name" type="text" required class="input input-bordered w-full mb-4" />

            <label for="email" class="block font-semibold">Email</label>
            <input id="email" name="email" type="email" required class="input input-bordered w-full mb-4" />

            <label for="phone" class="block font-semibold">Phone Number</label>
            <input id="phone" name="phone" type="tel" required class="input input-bordered w-full mb-4" />

            <label for="shipping_address" class="block font-semibold">Shipping Address</label>
            <input id="shipping_address" name="shipping_address" type="text" required class="input input-bordered w-full mb-2" />
            <input id="shipping_unit" name="shipping_unit" type="text" placeholder="Unit / Apartment (optional)" class="input input-bordered w-full mb-4" />

            <label for="billing_address" class="block font-semibold">Billing Address</label>
            <input id="billing_address" name="billing_address" type="text" required class="input input-bordered w-full mb-2" />
            <input id="billing_unit" name="billing_unit" type="text" placeholder="Unit / Apartment (optional)" class="input input-bordered w-full mb-4" />

            <label class="block font-semibold mb-2">Card Details</label>
            <div id="card-element" class="p-3 border rounded mb-4"></div>
            <div id="card-errors" role="alert" class="text-red-600 mb-4"></div>

            <button id="submit" class="btn btn-primary w-full" type="submit">Pay</button>
        </form>
    </div>

    {{-- Order Summary --}}
    <div class="w-full max-w-md mx-auto bg-white p-6 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
        <div class="flex justify-between mb-2">
            <span>Subtotal:</span>
            <span>R<span id="summary-subtotal">{{ number_format($subtotal, 2) }}</span></span>
        </div>
        <div class="flex justify-between mb-2">
            <span>Shipping:</span>
            <span>R<span id="summary-shipping">{{ number_format($shipping, 2) }}</span></span>
        </div>
        <hr class="my-3">
        <div class="flex justify-between font-bold text-lg">
            <span>Total:</span>
            <span>R<span id="summary-total">{{ number_format($total, 2) }}</span></span>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        '::placeholder': { color: '#a0aec0' },
                    },
                    invalid: { color: '#fa755a' },
                },
            });
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            const submitBtn = document.getElementById('submit');
            const cardErrors = document.getElementById('card-errors');
            const shippingDropdown = document.getElementById('shipping');

            const subtotal = parseFloat(document.getElementById('subtotal').innerText);
            const shippingDisplay = document.getElementById('shipping-cost');
            const totalDisplay = document.getElementById('total-cost');
            const summaryShipping = document.getElementById('summary-shipping');
            const summaryTotal = document.getElementById('summary-total');

            function updateShippingAndTotal() {
                let shippingCost = 0;
                if (shippingDropdown.value === 'express') {
                    shippingCost = 100;
                } else {
                    shippingCost = 50;
                }
                const total = subtotal + shippingCost;

                shippingDisplay.textContent = shippingCost.toFixed(2);
                totalDisplay.textContent = total.toFixed(2);
                summaryShipping.textContent = shippingCost.toFixed(2);
                summaryTotal.textContent = total.toFixed(2);
            }

            shippingDropdown.addEventListener('change', updateShippingAndTotal);
            updateShippingAndTotal();

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                submitBtn.disabled = true;
                cardErrors.textContent = '';

                const shippingMethod = shippingDropdown.value;

                const { paymentMethod, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        name: form.name.value,
                        email: form.email.value,
                        phone: form.phone.value,
                        address: {
                            line1: form.billing_address.value,
                            line2: form.billing_unit.value,
                        }
                    }
                });

                if (error) {
                    cardErrors.textContent = error.message;
                    submitBtn.disabled = false;
                    return;
                }

                const response = await fetch('{{ route('checkout.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        name: form.name.value,
                        email: form.email.value,
                        phone: form.phone.value,
                        shipping_address: form.shipping_address.value,
                        shipping_unit: form.shipping_unit.value,
                        billing_address: form.billing_address.value,
                        billing_unit: form.billing_unit.value,
                        shipping_method: shippingMethod,
                        payment_method_id: paymentMethod.id,
                    }),
                });

                const result = await response.json();

                if (result.error) {
                    cardErrors.textContent = result.error;
                    submitBtn.disabled = false;
                    return;
                }

                if (result.requires_action) {
                    const { error: confirmError, paymentIntent } = await stripe.handleCardAction(result.payment_intent_client_secret);
                    if (confirmError) {
                        cardErrors.textContent = confirmError.message;
                        submitBtn.disabled = false;
                        return;
                    }

                    const confirmResponse = await fetch('{{ route('checkout.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            payment_method_id: paymentIntent.payment_method,
                            name: form.name.value,
                            email: form.email.value,
                            phone: form.phone.value,
                            shipping_address: form.shipping_address.value,
                            shipping_unit: form.shipping_unit.value,
                            billing_address: form.billing_address.value,
                            billing_unit: form.billing_unit.value,
                            shipping_method: shippingMethod,
                            payment_intent_id: paymentIntent.id,
                        }),
                    });

                    const confirmResult = await confirmResponse.json();

                    if (confirmResult.error) {
                        cardErrors.textContent = confirmResult.error;
                        submitBtn.disabled = false;
                        return;
                    }

                    if (confirmResult.success) {
                        alert('Payment successful! Thank you for your order.');
                        window.location.href = '{{ route('home') }}';
                    }
                } else if (result.success && result.redirect) {
                    window.location.href = result.redirect;
                }
            });
        });
    </script>
@endsection
