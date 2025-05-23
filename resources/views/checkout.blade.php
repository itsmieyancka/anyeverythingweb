@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Checkout</h1>

        <form id="payment-form" method="POST" action="{{ route('checkout.process') }}">
            @csrf

            <!-- Your existing user info fields -->
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Full Name</label>
                <input type="text" name="name" id="name"
                       value="{{ old('name') }}"
                       class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Add other fields: email, address, phone as you have -->

            <!-- Stripe Card Element -->
            <div class="mb-4">
                <label for="card-element" class="block font-semibold mb-1">Credit or debit card</label>
                <div id="card-element" class="w-full border rounded px-3 py-2"></div>
                <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>
            </div>

            <button id="submit" type="submit"
                    class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
                Place Order
            </button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        card.on('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            displayError.textContent = error ? error.message : '';
        });

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Create payment intent on server
            const response = await fetch("{{ route('stripe.paymentIntent') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ amount: 5000 }) // Replace with dynamic amount in cents
            });

            const data = await response.json();

            if(data.error){
                document.getElementById('card-errors').textContent = data.error;
                return;
            }

            const { clientSecret } = data;

            const result = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email') ? document.getElementById('email').value : '',
                    }
                }
            });

            if(result.error){
                document.getElementById('card-errors').textContent = result.error.message;
            } else {
                if(result.paymentIntent.status === 'succeeded'){
                    // Optionally submit the form or redirect after successful payment
                    form.submit();
                }
            }
        });
    </script>
@endsection






