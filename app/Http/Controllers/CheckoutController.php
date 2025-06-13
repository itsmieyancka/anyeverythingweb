<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Default shipping method is "standard"
        $shippingMethod = 'standard';
        $shipping = $this->calculateShipping($cart, $shippingMethod);

        $total = $subtotal + $shipping;

        return view('checkout', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'shippingMethod' => $shippingMethod,
            'total' => $total,
        ]);
    }


    private function calculateShipping($cart, $shippingMethod = 'standard')
    {
        return match ($shippingMethod) {
            'express' => 150,
            default => 50,
        };
    }


    public function process(Request $request)
    {
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
            'shipping_method' => 'required|in:standard,express',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $cart = session('cart', []);
            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $shipping = $this->calculateShipping($cart, $validated['shipping_method']);
            $total = $subtotal + $shipping;

            $amount = $total * 100; // Convert to cents for Stripe

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
                $platformEarnings = 0;

                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'paid',
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'total' => $total,
                    'shipping_method' => $validated['shipping_method'],
                    'shipping_address' => $validated['shipping_address'],
                    'billing_address' => $validated['billing_address'],
                    'platform_earnings' => 0,
                ]);

                foreach ($cart as $item) {
                    $vendor = Vendor::find($item['vendor_id']);
                    $commissionRate = $vendor->commission_rate ?? 10;
                    $itemTotal = $item['price'] * $item['quantity'];
                    $commission = $itemTotal * ($commissionRate / 100);
                    $platformEarnings += $commission;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'vendor_id' => $item['vendor_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'status' => 'pending',
                    ]);
                }

                $order->update([
                    'platform_earnings' => $platformEarnings,
                ]);

                session()->forget('cart');

                return response()->json(['success' => true, 'redirect' => route('order.confirmed', ['order' => $order->id])]);
            } else {
                return response()->json(['error' => 'Invalid PaymentIntent status']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function confirmed(Order $order)
    {
        return view('order.confirmed', compact('order'));
    }
}
