@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-10">

        <h1 class="text-3xl font-bold">Vendor Dashboard</h1>

        @can('viewAny', \App\Models\Product::class)
            <a href="{{ url('/admin/products') }}"
               class="btn btn-primary shadow-md">
                🛠️ Product Dashboard
            </a>
        @endcan


        {{-- Vendor Products --}}
        <section>
            <h2 class="text-xl font-semibold mb-4">Your Products</h2>

            @if ($products->isEmpty())
                <p class="text-gray-500">You have not listed any products yet.</p>
            @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($products as $product)
                        <div class="border rounded shadow-sm p-4 bg-white">
                            <img src="{{ $product->getFirstMediaUrl('images') ?? 'https://via.placeholder.com/150' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-48 object-cover rounded mb-4">
                            <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 mb-1">Price: R{{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-500 mb-2">Stock: {{ $product->stock }}</p>
                            <a href="{{ url(config('filament.path') . '/products/' . $product->id . '/edit') }}"
                               class="text-blue-600 hover:underline text-sm">
                                Edit Product
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        {{-- Vendor Orders --}}
        <section>
            <h2 class="text-xl font-semibold mt-10 mb-4">Recent Orders</h2>

            @if ($orders->isEmpty())
                <p class="text-gray-500">No orders yet.</p>
            @else
                <div class="space-y-6">
                    @foreach ($orders as $order)
                        <div class="border rounded p-4 bg-gray-50">
                            <p class="text-sm text-gray-700">Order #{{ $order->id }} by <strong>{{ $order->user->name }}</strong></p>
                            <p class="text-sm text-gray-500">Placed: {{ $order->created_at->format('F j, Y, g:i a') }}</p>

                            <ul class="mt-2 text-sm list-disc list-inside">
                                @foreach ($order->orderItems as $item)
                                    <li>
                                        {{ $item->product->name }} - Quantity: {{ $item->quantity }} - Total: R{{ number_format($item->total, 2) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

    </div>
@endsection
