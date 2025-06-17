@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">My Orders</h1>

        @if($orders->count() > 0)
            <div class="space-y-8">
                @foreach($orders as $order)
                    <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">

                        <h2 class="text-xl font-bold mb-4">Order #{{ $order->id }} - {{ $order->created_at->format('d M Y') }}</h2>

                        {{-- Order Summary --}}
                        <div class="mb-4">
                            <h3 class="font-semibold text-lg">Order Summary</h3>
                            <ul class="text-sm">
                                <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
                                <li><strong>Total:</strong> R{{ number_format($order->total, 2) }}</li>
                                <li><strong>Shipping Method:</strong> {{ ucfirst($order->shipping_method) }}</li>
                            </ul>
                        </div>

                        {{-- Timeline --}}
                        <h3 class="font-semibold text-lg mb-4">Track Your Order</h3>
                        <ul class="timeline mb-6">
                            <li>
                                <div class="timeline-start timeline-box">Order Placed</div>
                                <div class="timeline-middle">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"
                                         class="text-primary h-5 w-5">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <hr class="bg-primary" />
                            </li>
                            <li>
                                <hr class="bg-primary" />
                                <div class="timeline-middle">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"
                                         class="text-primary h-5 w-5">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="timeline-end timeline-box">Processing</div>
                                <hr />
                            </li>
                            <li>
                                <hr />
                                <div class="timeline-start timeline-box text-gray-400">Shipped</div>
                                <div class="timeline-middle text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"
                                         class="text-primary h-5 w-5">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <hr />
                            </li>
                            <li>
                                <hr />
                                <div class="timeline-start timeline-box text-gray-400">Delivered</div>
                                <div class="timeline-middle text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20"
                                         fill="currentColor"
                                         class="text-primary h-5 w-5">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                              clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </li>
                        </ul>

                        {{-- Order Items --}}
                        <h3 class="font-semibold text-lg mb-2">Items:</h3>
                        <ul class="list-disc list-inside mb-4">
                            @foreach($order->items as $item)
                                <li>
                                    {{ $item->product->name ?? 'Product' }}
                                    (Qty: {{ $item->quantity }})
                                    - R{{ number_format($item->price, 2) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            {{ $orders->links() }}

        @else
            <p>You have no orders yet.</p>
        @endif
    </div>
@endsection
