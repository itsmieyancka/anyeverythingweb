@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 p-2 text-left">Product</th>
                    <th class="border border-gray-300 p-2 text-left">Variation</th>
                    <th class="border border-gray-300 p-2 text-center">Quantity</th>
                    <th class="border border-gray-300 p-2 text-right">Price</th>
                    <th class="border border-gray-300 p-2 text-right">Subtotal</th>
                    <th class="border border-gray-300 p-2 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $key => $item)
                    @php
                        $product = \App\Models\Product::find($item['product_id']);
                        $variationSet = $item['variationSet'] ?? null;
                        $variationOptions = $variationSet ? $variationSet->variationOptions->pluck('value')->join(', ') : '';
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    @endphp
                    <tr>
                        <td class="border border-gray-300 p-2">
                            {{ $product->name ?? 'Product not found' }}
                        </td>
                        <td class="border border-gray-300 p-2">
                            {{ $variationOptions }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            {{ $item['quantity'] }}
                        </td>
                        <td class="border border-gray-300 p-2 text-right">
                            R{{ number_format($item['price'], 2) }}
                        </td>
                        <td class="border border-gray-300 p-2 text-right">
                            R{{ number_format($subtotal, 2) }}
                        </td>
                        <td class="border border-gray-300 p-2 text-center">
                            <form action="{{ route('cart.remove', $key) }}" method="POST" onsubmit="return confirm('Remove this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr class="font-bold bg-gray-100">
                    <td colspan="4" class="border border-gray-300 p-2 text-right">Total</td>
                    <td class="border border-gray-300 p-2 text-right">R{{ number_format($total, 2) }}</td>
                    <td class="border border-gray-300 p-2"></td>
                </tr>
                </tbody>
            </table>

            <div class="mt-6 flex gap-4">
                <a href="{{ url('/') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Continue Shopping</a>
                <a href="{{ route('checkout.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Proceed to Checkout</a>
            </div>
        @else
            <p>Your cart is empty.</p>
            <a href="{{ url('/') }}" class="text-blue-600 hover:underline">Go shopping</a>
        @endif
    </div>
@endsection
