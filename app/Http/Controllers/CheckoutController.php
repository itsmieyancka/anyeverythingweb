<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller

{
    public function index()
    {
        $cart = session('cart', []);

        foreach ($cart as &$item) {
            $item['variationSet'] = $item['variation_set_id']
                ? ProductVariationSet::with('variationOptions')->find($item['variation_set_id'])
                : null;
            $item['product'] = Product::find($item['product_id']);
        }

        $subtotal = collect($cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));

        return view('checkout', compact('cart', 'subtotal'));
    }


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
            'shipping_method' => 'required|in:standard,express',
        ]);

        // Calculate subtotal
        $subtotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // Shipping costs
        $shippingCosts = [
            'standard' => 40,
            'express' => 80,
        ];
        $shippingCost = $shippingCosts[$request->input('shipping_method')];

        // Calculate total
        $total = $subtotal + $shippingCost;

        // Calculate commission per item based on vendor commission rate
        $commissionTotal = 0;

        foreach ($cart as $item) {
            $vendorId = null;

            // Find vendor_id depending on variation or product
            if (!empty($item['variation_set_id'])) {
                $variationSet = ProductVariationSet::find($item['variation_set_id']);
                if ($variationSet) {
                    $vendorId = $variationSet->vendor_id;
                    $variationSet->decrement('stock', $item['quantity']);
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $vendorId = $product->vendor_id;
                    $product->decrement('stock', $item['quantity']);
                }
            }

            // Get commission rate from vendors table for this vendor (default 0 if not found)
            $commissionRate = 0;
            if ($vendorId) {
                $commissionRate = DB::table('vendors')
                    ->where('user_id', $vendorId)
                    ->value('commission_rate') ?? 0;
            }

            $commissionRateDecimal = $commissionRate / 100;

            // Calculate commission for this cart item
            $itemCommission = $item['price'] * $item['quantity'] * $commissionRateDecimal;

            $commissionTotal += $itemCommission;
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'status' => 'processing',
            'subtotal' => $subtotal,
            'shipping' => $shippingCost,
            'platform_earnings' => $commissionTotal,
            'total' => $total,

        ]);

        // Create order items with vendor_id
        foreach ($cart as $item) {
            $vendorId = null;

            if (!empty($item['variation_set_id'])) {
                $variationSet = ProductVariationSet::find($item['variation_set_id']);
                if ($variationSet) {
                    $vendorId = $variationSet->vendor_id;
                }
            } else {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $vendorId = $product->vendor_id;
                }
            }

            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'vendor_id' => $vendorId,
            ]);
        }

        session()->forget('cart');

        return redirect()->route('order.confirmed', $order);
    }

    public function confirmed(Order $order)
    {
        return view('order.confirmed', compact('order'));
    }
}
