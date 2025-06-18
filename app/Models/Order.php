<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'shipping',     // corrected key: was 'shipping' in your original fillable
        'total',
        'platform_earnings',
        'shipping_address',
        'shipping_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
