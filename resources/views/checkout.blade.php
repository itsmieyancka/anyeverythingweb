@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Checkout</h2>

        <form id="payment-form">
            @csrf

            {{-- Name --}}
            <label for="name" class="block font-semibold">Full Name</label>
            <input id="name" name="name" type="text" required class="input input-bordered w-full mb-4" />

            {{-- Email --}}
            <label for="email" class="block font-semibold">Email</label>
            <input id="email" name="email" type="email" required class="input input-bordered w-full mb-4" />

            {{-- Phone --}}
            <label for="phone" class="block font-semibold">Phone Number</label>
            <input id="phone" name="phone" type="tel" required class="input input-bordered w-full mb-4" />

            {{-- Shipping Address --}}
            <label for="shipping_address" class="block font-semibold">Shipping Address</label>
            <input id="shipping_address" name="shipping_address" type="text" required class="input input-bordered w-full mb-2" />
            <input id="shipping_unit" name="shipping_unit" type="text" placeholder="Unit / Apartment (optional)" class="input input-bordered w-full mb-4" />

            {{-- Billing Address --}}
            <label for="billing_address" class="block font-semibold">Billing Address</label>
            <input id="billing_address" name="billing_address" type="text" required class="input input-bordered w-full mb-2" />
            <input id="billing_unit" name="billing_unit" type="text" placeholder="Unit / Apartment (optional)" class="input input-bordered w-full mb-4" />

            {{-- Stripe Card Element --}}
            <label class="block font-semibold mb-2">Card Details</label>
            <div id="card-element" class="p-3 border rounded mb-4"></div>

            <div id="card-errors" role="alert" class="text-red-600 mb-4"></div>

            <button id="submit" class="btn btn-primary w-full" type="submit">Pay</button>
        </form>
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

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                submitBtn.disabled = true;
                cardErrors.textContent = '';

                // Create payment method
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

                // Send payment method ID and form data to backend
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
                    // Handle 3D Secure authentication
                    const { error: confirmError, paymentIntent } = await stripe.handleCardAction(
                        result.payment_intent_client_secret
                    );

                    if (confirmError) {
                        cardErrors.textContent = confirmError.message;
                        submitBtn.disabled = false;
                        return;
                    }

                    // After authentication, confirm payment on server again
                    const confirmResponse = await fetch('{{ route('checkout.process') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            payment_method_id: paymentIntent.payment_method,
                            // Include other necessary fields if required
                            name: form.name.value,
                            email: form.email.value,
                            phone: form.phone.value,
                            shipping_address: form.shipping_address.value,
                            shipping_unit: form.shipping_unit.value,
                            billing_address: form.billing_address.value,
                            billing_unit: form.billing_unit.value,
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
                } else if (result.success) {
                    alert('Payment successful! Thank you for your order.');
                    window.location.href = '{{ route('home') }}';
                }
            });
        });
    </script>
@endsection

