@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-6">Checkout</h1>

        <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input type="email" name="email" required class="input input-bordered w-full">
            </div>

            <div>
                <label class="block text-sm font-medium">Card Number</label>
                <input type="text" name="card_number" placeholder="4242 4242 4242 4242" required class="input input-bordered w-full">
            </div>

            <div class="mt-6">
                <h2 class="text-lg font-semibold mb-2">Order Summary</h2>
                <ul class="divide-y divide-gray-200">
                    @foreach ($cart as $item)
                        <li class="py-2 flex justify-between">
                            <div>
                                <p>{{ $item['product']->name }}</p>
                                @if ($item['variationSet'])
                                    <p class="text-sm text-gray-500">
                                        @foreach ($item['variationSet']->variationOptions as $option)
                                            {{ $option->value }}@if (!$loop->last), @endif
                                        @endforeach
                                    </p>
                                @endif
                            </div>
                            <div>
                                <p>x{{ $item['quantity'] }}</p>
                                <p class="text-right">R{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="text-right mt-4 font-semibold text-lg">
                    Total: R{{ number_format(collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']), 2) }}
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-4">Pay</button>
        </form>
    </div>
@endsection
