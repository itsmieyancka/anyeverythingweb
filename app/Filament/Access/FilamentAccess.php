<?php

namespace App\Filament\Access;

use Illuminate\Support\Facades\Auth;

class FilamentAccess
{
    public static function check(): bool
    {
        $user = Auth::user();
        return $user && $user->hasAnyRole(['admin', 'vendor']);
    }
}
