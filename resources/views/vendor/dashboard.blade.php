<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vendor Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Welcome Vendor!</p>

                    <h1 class="mt-6 mb-4 text-2xl font-bold">My Orders</h1>

                    @forelse ($orders as $order)
                        <div class="mb-6 border border-gray-300 rounded p-4">
                            <h3 class="text-lg font-semibold">
                                Order #{{ $order->id }} - Status: <span class="capitalize">{{ $order->status }}</span>
                            </h3>
                            <p class="text-sm text-gray-600">
                                Customer: {{ $order->user->name }} ({{ $order->user->email }})
                            </p>

                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($order->orderItems as $item)
                                    <li class="mb-2">
                                        <strong>Product:</strong> {{ $item->product->name }} <br>
                                        <strong>Quantity:</strong> {{ $item->quantity }} <br>
                                        <strong>Price:</strong> R{{ number_format($item->price, 2) }} <br>
                                        <strong>Status:</strong> <span class="capitalize">{{ $item->status }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p>No orders found.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

