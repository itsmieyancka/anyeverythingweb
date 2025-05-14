<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        return view('user.dashboard');
    }
}
