<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->withErrors('Your cart is empty.');
        }

        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'card_number' => 'required|string',
            'expiry' => 'required|string',
            'cvc' => 'required|string',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'processing',
            'total' => collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']),
            'shipping_address' => $request->input('address'),
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'variation_set_id' => $item['variation_set_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Stock reduction
            if ($item['variation_set_id']) {
                $variationSet = ProductVariationSet::find($item['variation_set_id']);
                if ($variationSet) {
                    $variationSet->decrement('stock', $item['quantity']);
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }
            }
        }

        session()->forget('cart');

        return redirect()->route('order.confirmed', $order);
    }

    public function confirmed(Order $order)
    {
        return view('checkout.confirmed', compact('order'));
    }
}
