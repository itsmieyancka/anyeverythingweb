<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Session;
use App\Models\Order;         // You need an Order model for saving orders
use App\Models\OrderItem;     // And OrderItem model for order lines
use App\Models\Product;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        // Load product and variationSet models for display
        foreach ($cart as &$item) {
            $item['product'] = Product::find($item['product_id']);
            if ($item['variation_set_id']) {
                $item['variationSet'] = $item['variationSet'] ?? null;
            }
        }

        return view('checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_unit' => 'nullable|string',
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

        $shippingCost = $request->shipping_method === 'express' ? 10000 : 5000; // in cents (R100 or R50)
        $totalAmount = $subtotal * 100 + $shippingCost; // convert Rands to cents

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'zar',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'receipt_email' => $request->email,
                'metadata' => [
                    'user_email' => $request->email,
                ],
            ]);

            if ($paymentIntent->status == 'requires_action' && $paymentIntent->next_action->type == 'use_stripe_sdk') {
                // Tell the frontend to handle the action
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ]);
            } elseif ($paymentIntent->status == 'succeeded') {
                // Payment successful, create order

                $order = $this->createOrder($request, $cart, $subtotal, $shippingCost / 100);

                // Clear cart
                session()->forget('cart');

                return response()->json([
                    'success' => true,
                    'redirect' => route('order.confirmed', ['order' => $order->id]),
                ]);
            } else {
                return response()->json(['success' => false, 'error' => 'Payment failed. Please try another payment method.'], 422);
            }
        } catch (ApiErrorException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
    }

    protected function createOrder(Request $request, array $cart, float $subtotal, float $shippingCost)
    {
        // Basic example: create Order and OrderItems
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
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'variation_set_id' => $item['variation_set_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return $order;
    }
}


