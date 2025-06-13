<?php

namespace App\Policies;

use App\Models\ProductVariationSet;
use App\Models\User;

class ProductVariationSetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'vendor']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductVariationSet $productVariationSet): bool
    {
        return $user->hasAnyRole(['admin', 'vendor']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'vendor']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductVariationSet $productVariationSet): bool
    {
        return $user->hasAnyRole(['admin', 'vendor']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductVariationSet $productVariationSet): bool
    {
        return $user->hasRole('admin'); // only admin can delete
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductVariationSet $productVariationSet): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductVariationSet $productVariationSet): bool
    {
        return $user->hasRole('admin');
    }
}

