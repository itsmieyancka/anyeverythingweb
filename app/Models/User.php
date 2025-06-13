<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected string $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship with Vendor (if the user is a vendor)
     */
    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    /**
     * Relationship with products (if user is a vendor)
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id', 'id');
    }

    /**
     * Relationship with orders (as a buyer)
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    /**
     * Relationship with product ratings (as a reviewer)
     */
    public function productRatings()
    {
        return $this->hasMany(ProductRating::class);
    }

    /**
     * Check if the user has purchased a specific product
     */
    public function hasPurchased(Product $product)
    {
        return $this->orders()
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();
    }

    /**
     * Determine if the user can access the Filament admin panel
     */
    public function canAccessFilament(): bool
    {
        return $this->hasAnyRole(['admin', 'vendor']);
    }
}
