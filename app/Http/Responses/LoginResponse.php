<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;

class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            // Redirect admin to Filament admin dashboard
            return redirect(Filament::getUrl());
        }

        // Redirect other users (non-admins) somewhere else (e.g. user dashboard)
        return redirect()->intended('/user/dashboard');
    }
}



