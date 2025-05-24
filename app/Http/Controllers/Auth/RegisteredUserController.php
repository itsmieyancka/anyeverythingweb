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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role and create vendor if needed
        if ($request->has('register_as_vendor')) {
            $user->assignRole('vendor');

            Vendor::create([
                'user_id' => $user->id,
                'business_name' => $request->input('business_name', $user->name . "'s Store"),
            ]);
        } else {
            $user->assignRole('user');
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect to appropriate dashboard
        if ($user->hasRole('vendor')) {
            return redirect()->route('vendor.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}
