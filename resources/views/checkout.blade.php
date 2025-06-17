@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Checkout</h2>

        <form id="payment-form">
            @csrf

            <div>
                <label for="email">Email:</label>
                <input id="email" name="email" type="email" required value="{{ old('email', auth()->user()->email ?? '') }}">
            </div>

            <div>
                <label for="shipping_address">Shipping Address:</label>
                <input id="shipping_address" name="shipping_address" type="text" required>
            </div>

            <div>
                <label for="shipping_unit">Unit / Apartment (optional):</label>
                <input id="shipping_unit" name="shipping_unit" type="text">
            </div>

            <div>
                <label for="phone">Phone Number:</label>
                <input id="phone" name="phone" type="tel" required>
            </div>

            <div>
                <label for="shipping_method">Shipping Method:</label>
                <select id="shipping_method" name="shipping_method" required>
                    <option value="standard" selected>Standard (50 ZAR)</option>
                    <option value="express">Express (150 ZAR)</option>
                </select>
            </div>

            <hr>

            <h4>Payment Details</h4>

            <div id="card-element" style="padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <!-- Stripe.js injects the Card Element here -->
            </div>

            <div id="card-errors" role="alert" style="color: red; margin-top: 10px;"></div>

            <button id="submit-button" type="submit" style="margin-top: 20px;">Pay {{ number_format($total, 2) }} ZAR</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stripe = Stripe('{{ $stripeKey }}');
            const elements = stripe.elements();

            // Create and mount the Card Element
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const cardErrors = document.getElementById('card-errors');

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                submitButton.disabled = true;
                cardErrors.textContent = '';

                // Create payment method with card details
                const {error, paymentMethod} = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        email: form.email.value,
                        phone: form.phone.value,
                        address: {
                            line1: form.shipping_address.value,
                            line2: form.shipping_unit.value || '',
                        },
                    },
                });

                if (error) {
                    cardErrors.textContent = error.message;
                    submitButton.disabled = false;
                    return;
                }

                // Prepare data to send to backend
                const data = {
                    _token: '{{ csrf_token() }}',
                    email: form.email.value,
                    shipping_address: form.shipping_address.value,
                    shipping_unit: form.shipping_unit.value,
                    phone: form.phone.value,
                    payment_method_id: paymentMethod.id,
                    shipping_method: form.shipping_method.value,
                };

                try {
                    const response = await fetch('{{ route('checkout.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': data._token,
                        },
                        body: JSON.stringify(data),
                    });

                    const result = await response.json();

                    if (result.success) {
                        // Redirect to order confirmation page
                        window.location.href = result.redirect;
                    } else {
                        cardErrors.textContent = result.error || 'Payment failed.';
                        submitButton.disabled = false;
                    }
                } catch (err) {
                    cardErrors.textContent = 'An unexpected error occurred.';
                    submitButton.disabled = false;
                }
            });
        });
    </script>
@endsection

