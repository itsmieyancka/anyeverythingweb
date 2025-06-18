<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index()
    {
        // Get vendor ID linked to the authenticated user
        $vendorId = Vendor::where('user_id', auth()->id())->value('id');

        // Fetch orders that include products belonging to this vendor
        $orders = Order::whereHas('Items', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->with([
                'orderItems' => function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId)->with('product');
                },
                'user'
            ])
            ->latest()
            ->get();

        // Fetch vendor's own products
        $products = Product::where('vendor_id', $vendorId)
            ->with(['media', 'vendor'])
            ->latest()
            ->get();

        return view('vendor.dashboard', compact('orders', 'products'));
    }
}
