<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form.
     */
    public function index()
    {
        $cart = session('cart', []);

        foreach ($cart as &$item) {
            $item['product'] = Product::find($item['product_id']);
            $item['variationSet'] = $item['variation_set_id']
                ? ProductVariationSet::with('variationOptions')->find($item['variation_set_id'])
                : null;
        }

        return view('checkout', compact('cart'));
    }

    /**
     * Process the checkout and mock payment.
     */
    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'card_number' => 'required|string',
            'expiry' => 'required|string',
            'cvv' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        // Mock payment: if any test card number is used, accept it.
        // In real case, this is where you'd call Stripe or another gateway.

        // Create order
        $order = Order::create([
            'user_id' => Auth::id() ?? null,
            'name' => $request->name,
            'address' => $request->address,
            'status' => 'Processing',
            'total' => collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            }),
        ]);

        // Create order items and reduce stock
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variation_set_id' => $item['variation_set_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            if ($item['variation_set_id']) {
                $variationSet = ProductVariationSet::find($item['variation_set_id']);
                if ($variationSet && $variationSet->stock >= $item['quantity']) {
                    $variationSet->stock -= $item['quantity'];
                    $variationSet->save();
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product && $product->stock >= $item['quantity']) {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
            }
        }

        session()->forget('cart');

        return redirect()->route('order.confirmed', $order);
    }

    /**
     * Show the confirmation page after checkout.
     */
    public function confirmed(Order $order)
    {
        return view('checkout.confirmed', compact('order'));
    }
}
