<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function userOrders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items.product') // eager load order items and product info
            ->orderBy('created_at', 'desc')
            ->paginate(10); // paginate if many orders

        return view('profile.orders', compact('orders'));
    }
}
