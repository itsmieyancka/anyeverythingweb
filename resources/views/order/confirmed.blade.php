@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-green-600">🎉 Order Confirmed</h2>
        <p class="mb-4">Thank you for your purchase. Your order is being processed.</p>

        <div class="mb-6">
            <h3 class="font-semibold text-lg">Order Summary</h3>
            <ul class="text-sm">
                <li><strong>Order ID:</strong> {{ $order->id }}</li>
                <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
                <li><strong>Total:</strong> R{{ number_format($order->total, 2) }}</li>
            </ul>
        </div>

        <h3 class="font-semibold text-lg mb-4">Track Your Order</h3>
        <ul class="timeline">
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
    </div>
@endsection
