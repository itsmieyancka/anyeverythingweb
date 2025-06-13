<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariationSet extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'product_id',
        'price',
        'stock',
        'is_active',
    ];

    // Cast is_active to boolean for convenience
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Each variation set belongs to one product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Many-to-many relationship with VariationOption through pivot table
    public function variationOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            VariationOption::class,
            'product_variation_set_variation_option', // pivot table name
            'product_variation_set_id', // foreign key on pivot for this model
            'variation_option_id' // foreign key on pivot for related model
        );
    }

    /**
     * Helper method to get variation option values as a comma-separated string.
     *
     * @return string
     */
    public function variationOptionValues(): string
    {
        return $this->variationOptions->pluck('value')->join(', ');
    }
}



