<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariationSet extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'price', 'stock', 'is_active'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOptions()
    {
        return $this->belongsToMany(VariationOption::class);
    }
}
