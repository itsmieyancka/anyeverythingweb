<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Add vendor business_name validation if vendor registration selected
            'business_name' => ['sometimes', 'string', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->has('register_as_vendor')) {
            $user->assignRole('vendor');

            // Create vendor record linked to this user
            $vendor = Vendor::create([
                'user_id' => $user->id,
                'business_name' => $request->input('business_name', $user->name . "'s Store"),
            ]);

            // OPTIONAL: you could store vendor id in session or do other logic here
        } else {
            $user->assignRole('user');
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect to dashboard depending on role
        if ($user->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}
