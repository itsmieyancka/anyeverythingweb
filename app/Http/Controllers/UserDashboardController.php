<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // add this
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
public function index()
{
$products = Product::where('is_active', true)->get();
$categories = Category::all();  // fetch all categories

return view('user.dashboard', compact('products', 'categories'));
}
}

