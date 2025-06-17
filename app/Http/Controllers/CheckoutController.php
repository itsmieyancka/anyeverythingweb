<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\ProductVariationSet;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shippingMethod = 'standard';
        $shipping = $this->calculateShipping($cart, $shippingMethod);
        $total = $subtotal + $shipping;

        return view('checkout', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'shippingMethod' => $shippingMethod,
            'total' => $total,
            'stripeKey' => config('services.stripe.key'),
        ]);
    }

    /**
     * Handle payment and order creation (mock payment, no 3DS).
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'You must be logged in to checkout.'], 401);
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:1000',
            'shipping_unit' => 'nullable|string|max:50',
            'phone' => 'required|string|max:20',
            'payment_method_id' => 'required|string',
            'shipping_method' => 'required|in:standard,express',
        ]);

        // Use Stripe test secret key (never use live key for mock/test)
        Stripe::setApiKey(config('services.stripe.secret') ?? 'sk_test_4eC39HqLyjWDarjtT1zdp7dc');

        try {
            $cart = session('cart', []);

            // Stock check
            foreach ($cart as $item) {
                if (!empty($item['variation_set_id'])) {
                    $variationSet = ProductVariationSet::find($item['variation_set_id']);
                    if (!$variationSet || $variationSet->stock < $item['quantity']) {
                        return response()->json(['error' => 'Insufficient stock for a product.'], 422);
                    }
                } else {
                    $product = Product::find($item['product_id']);
                    if (!$product || $product->stock < $item['quantity']) {
                        return response()->json(['error' => 'Insufficient stock for a product.'], 422);
                    }
                }
            }

            $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
            $shipping = $this->calculateShipping($cart, $validated['shipping_method']);
            $total = $subtotal + $shipping;

            // Create Stripe PaymentIntent (no 3DS, instant confirmation)
            $paymentIntent = PaymentIntent::create([
                'amount' => $total * 100, // cents
                'currency' => 'zar',
                'payment_method_types' => ['card'],
                'payment_method' => $validated['payment_method_id'],
                'confirm' => true,
                'off_session' => true,
                'confirmation_method' => 'automatic',
                'payment_method_options' => [
                    'card' => [
                        'request_three_d_secure' => 'never', // <-- disables 3DS
                    ],
                ],
                'receipt_email' => $validated['email'],
                'shipping' => [
                    'address' => [
                        'line1' => $validated['shipping_address'],
                        'line2' => $validated['shipping_unit'] ?? '',
                    ],
                    'phone' => $validated['phone'],
                ],
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            // If payment succeeded
            if (in_array($paymentIntent->status, ['succeeded', 'processing'])) {
                $platformEarnings = 0;

                $order = Order::create([
                    'user_id' => $user->id,
                    'status' => 'paid',
                    'subtotal' => $subtotal,
                    'shipping' => $shipping,
                    'total' => $total,
                    'shipping_method' => $validated['shipping_method'],
                    'shipping_address' => $validated['shipping_address'],
                    'shipping_unit' => $validated['shipping_unit'] ?? null,
                    'platform_earnings' => 0,
                    'payment_intent_id' => $paymentIntent->id,
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
                        'variation_set_id' => $item['variation_set_id'] ?? null,
                    ]);

                    if (!empty($item['variation_set_id'])) {
                        $variationSet = ProductVariationSet::lockForUpdate()->find($item['variation_set_id']);
                        if ($variationSet) {
                            $variationSet->stock = max(0, $variationSet->stock - $item['quantity']);
                            $variationSet->save();
                        }
                    } else {
                        $product = Product::lockForUpdate()->find($item['product_id']);
                        if ($product) {
                            $product->stock = max(0, $product->stock - $item['quantity']);
                            $product->save();
                        }
                    }
                }

                $order->update(['platform_earnings' => $platformEarnings]);

                // Clear cart
                session()->forget('cart');

                return response()->json([
                    'success' => true,
                    'redirect' => route('order.confirmed', ['order' => $order->id])
                ]);
            }

            return response()->json(['error' => 'Payment failed: ' . $paymentIntent->status], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the order confirmation page.
     */
    public function confirmed(Order $order)
    {
        return view('order.confirmed', compact('order'));
    }

    /**
     * Calculate shipping cost.
     */
    private function calculateShipping($cart, $method = 'standard')
    {
        return match ($method) {
            'express' => 150,
            default => 50,
        };
    }
}

