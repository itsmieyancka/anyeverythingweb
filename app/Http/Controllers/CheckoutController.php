<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        // Load product details for display
        foreach ($cart as &$item) {
            $item['product'] = Product::find($item['product_id']);
            if ($item['variation_set_id']) {
                $item['variationSet'] = $item['variationSet'] ?? null;
            }
            $item['total'] = $item['price'] * $item['quantity'];
        }

        $subtotal = array_sum(array_column($cart, 'total'));
        $shippingOptions = [
            'standard' => ['price' => 50, 'label' => 'Standard Delivery (3-5 days)'],
            'express' => ['price' => 100, 'label' => 'Express Delivery (1-2 days)']
        ];

        return view('checkout', compact('cart', 'subtotal', 'shippingOptions'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_unit' => 'nullable|string|max:50',
            'shipping_method' => 'required|in:standard,express',
            'payment_method_id' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'error' => 'Your cart is empty.'], 422);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingCost = $request->shipping_method === 'express' ? 10000 : 5000; // in cents
        $totalAmount = $subtotal * 100 + $shippingCost;

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'zar',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'automatic', // changed from 'manual'
                'confirm' => true,
                'receipt_email' => $request->email,
                'metadata' => [
                    'user_email' => $request->email,
                    'phone' => $request->phone,
                ],
                'shipping' => [
                    'address' => [
                        'line1' => $request->shipping_address,
                        'line2' => $request->shipping_unit ?? '',
                    ],
                    'name' => $request->email,
                    'phone' => $request->phone,
                ],
            ]);

            // With automatic confirmation, handle success or failure directly
            if ($paymentIntent->status == 'succeeded') {
                $order = $this->createOrder($request, $cart, $subtotal, $shippingCost / 100);
                session()->forget('cart');

                return response()->json([
                    'success' => true,
                    'redirect' => route('order.confirmed', ['order' => $order->id]),
                ]);
            } else {
                // Payment failed or requires further action client-side (rare with automatic)
                return response()->json([
                    'success' => false,
                    'error' => 'Payment failed or requires additional authentication.',
                ], 422);
            }
        } catch (ApiErrorException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    protected function createOrder(Request $request, array $cart, float $subtotal, float $shippingCost)
    {
        $order = Order::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'shipping_address' => $request->shipping_address,
            'shipping_unit' => $request->shipping_unit,
            'shipping_method' => $request->shipping_method,
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
            'status' => 'processing',
            'stripe_payment_intent' => $request->payment_intent_id ?? null,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variation_set_id' => $item['variation_set_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return $order;
    }
}



