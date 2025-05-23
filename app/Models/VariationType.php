<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariationType extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'slug'];

    /**
     * Each VariationType belongs to a single Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * A VariationType has many VariationOptions
     * e.g. "Size" has "Small", "Medium", "Large"
     */
    public function variationOptions(): HasMany
    {
        return $this->hasMany(VariationOption::class);
    }
}
