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
                <li>
                    <a href="{{ route('shop.index') }}" class="ml-4 text-green-600 font-semibold hover:underline">
                        &larr; Continue Shopping
                    </a>
                </li>
            </ol>
        </nav>

        <h2 class="text-2xl font-semibold mb-4">Checkout</h2>

        <form id="payment-form">
            @csrf

            <label for="name" class="block mb-1 font-medium">Full Name</label>
            <input type="text" id="name" name="name" required class="w-full mb-4 p-2 border rounded" />

            <label for="email" class="block mb-1 font-medium">Email</label>
            <input type="email" id="email" name="email" required class="w-full mb-4 p-2 border rounded" />

            <label for="phone" class="block mb-1 font-medium">Phone Number</label>
            <input type="text" id="phone" name="phone" required class="w-full mb-4 p-2 border rounded" />

            <label for="shipping_address" class="block mb-1 font-medium">Shipping Address</label>
            <input type="text" id="shipping_address" name="shipping_address" required class="w-full mb-4 p-2 border rounded" />

            <label for="shipping_unit" class="block mb-1 font-medium">Shipping Unit (optional)</label>
            <input type="text" id="shipping_unit" name="shipping_unit" class="w-full mb-4 p-2 border rounded" />

            <label for="billing_address" class="block mb-1 font-medium">Billing Address</label>
            <input type="text" id="billing_address" name="billing_address" required class="w-full mb-4 p-2 border rounded" />

            <label for="billing_unit" class="block mb-1 font-medium">Billing Unit (optional)</label>
            <input type="text" id="billing_unit" name="billing_unit" class="w-full mb-4 p-2 border rounded" />

            <label for="shipping_method" class="block mb-1 font-medium">Shipping Method</label>
            <select id="shipping_method" name="shipping_method" required class="w-full mb-4 p-2 border rounded">
                <option value="standard" selected>Standard</option>
                <option value="express">Express</option>
            </select>

            <label class="block mb-1 font-medium">Card Details</label>
            <div id="card-element" class="mb-4 p-2 border rounded"></div>

            <div id="payment-errors" class="text-red-600 mb-4"></div>

            <button type="submit" id="submit-button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Pay Now
            </button>
        </form>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const errorsDiv = document.getElementById('payment-errors');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorsDiv.textContent = '';
            submitButton.disabled = true;

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: form.name.value,
                    email: form.email.value,
                },
            });

            if (error) {
                errorsDiv.textContent = error.message;
                submitButton.disabled = false;
                return;
            }

            const data = {
                name: form.name.value,
                email: form.email.value,
                phone: form.phone.value,
                shipping_address: form.shipping_address.value,
                shipping_unit: form.shipping_unit.value,
                billing_address: form.billing_address.value,
                billing_unit: form.billing_unit.value,
                shipping_method: form.shipping_method.value,
                payment_method_id: paymentMethod.id,
                _token: '{{ csrf_token() }}'
            };

            try {
                const response = await fetch('{{ route("checkout.process") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(data),
                });
                const result = await response.json();

                if (result.error) {
                    errorsDiv.textContent = result.error;
                    submitButton.disabled = false;
                } else if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    errorsDiv.textContent = 'Unexpected error occurred.';
                    submitButton.disabled = false;
                }
            } catch (err) {
                errorsDiv.textContent = 'Network or server error. Please try again.';
                submitButton.disabled = false;
            }
        });
    </script>
@endsection
