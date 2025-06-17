@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow mt-10">
        <h1 class="text-2xl font-bold mb-4">Checkout</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('checkout.process') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block font-medium">Name</label>
                <input type="text" name="name" id="name" required class="w-full border rounded p-2">
            </div>

            <div>
                <label for="address" class="block font-medium">Shipping Address</label>
                <textarea name="address" id="address" required class="w-full border rounded p-2"></textarea>
            </div>

            <div class="mt-6 border-t pt-4">
                <h2 class="text-lg font-semibold mb-2">Payment Details (Mock)</h2>

                <div>
                    <label for="card_number" class="block">Card Number</label>
                    <input type="text" name="card_number" id="card_number" required class="w-full border rounded p-2" placeholder="4242 4242 4242 4242">
                </div>

                <div class="flex gap-4 mt-2">
                    <div class="w-1/2">
                        <label for="expiry" class="block">Expiry</label>
                        <input type="text" name="expiry" id="expiry" required class="w-full border rounded p-2" placeholder="12/34">
                    </div>

                    <div class="w-1/2">
                        <label for="cvc" class="block">CVC</label>
                        <input type="text" name="cvc" id="cvc" required class="w-full border rounded p-2" placeholder="123">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full mt-6 bg-blue-600 text-white rounded p-3 hover:bg-blue-700">
                Pay Now
            </button>
        </form>
    </div>
@endsection
