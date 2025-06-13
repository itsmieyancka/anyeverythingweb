<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'vendor']);
    }

    public function view(User $user, Product $product): bool
    {
        return $user->hasRole('admin') || $product->vendor_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Only admins can create products
        return $user->hasAnyRole(['admin', 'vendor']);
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('admin') || $product->vendor_id === $user->id;
    }

    public function delete(User $user, Product $product): bool
    {
        // Only admins can delete products
        return $user->hasRole('admin');
    }


    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'vendor']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'vendor']);
    }
}
