<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendorId = auth()->id();

        // Fetch orders that include products belonging to this vendor
        $orders = Order::whereHas('orderItems', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->with(['orderItems' => function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            }, 'user']) // eager load customer info
            ->get();

        return view('vendor.dashboard', compact('orders'));
    }
}


