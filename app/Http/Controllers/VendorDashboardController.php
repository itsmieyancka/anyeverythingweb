<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VendorDashboardController extends Controller
{
    public function index()
    {
        return view('vendor.dashboard');
    }
}

