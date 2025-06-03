<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index()
    {
        // Show checkout page to authenticated user
        return view('checkout');
    }

    public function process(Request $request)
    {
        // Ensure user is authenticated (extra safety)
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'You must be logged in to checkout.'], 401);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:1000',
            'shipping_unit' => 'nullable|string|max:50',
            'billing_address' => 'required|string|max:1000',
            'billing_unit' => 'nullable|string|max:50',
            'phone' => 'required|string|max:20',
            'payment_method_id' => 'required|string',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Replace with your actual cart total in cents
            $amount = 1000; // e.g., 10.00 ZAR

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'zar',
                'payment_method_types' => ['card'],
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'receipt_email' => $validated['email'],
                'shipping' => [
                    'name' => $validated['name'],
                    'address' => [
                        'line1' => $validated['shipping_address'],
                        'line2' => $validated['shipping_unit'] ?? '',
                    ],
                    'phone' => $validated['phone'],
                ],
                'metadata' => [
                    'billing_address' => $validated['billing_address'],
                    'billing_unit' => $validated['billing_unit'] ?? '',
                ],
            ]);

            if ($paymentIntent->status === 'requires_action' && $paymentIntent->next_action->type === 'use_stripe_sdk') {
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ]);
            } elseif ($paymentIntent->status === 'succeeded') {
                // Create Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'paid',
                    'total' => $amount / 100,
                ]);

                // Example cart items from session (replace with your cart logic)
                $cartItems = session('cart', []);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'vendor_id' => $item['vendor_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'status' => 'pending',
                    ]);
                }

                session()->forget('cart');

                return response()->json(['success' => true, 'message' => 'Payment successful! Thank you for your order.']);
            } else {
                return response()->json(['error' => 'Invalid PaymentIntent status']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
