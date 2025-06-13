<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->get();

        return view('user.dashboard', compact('products'));

    }
}

