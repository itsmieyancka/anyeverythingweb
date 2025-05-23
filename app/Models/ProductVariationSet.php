<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\VariationOption;

class ProductVariationSet extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'variation_option_ids', 'price', 'stock'];

    protected $casts = [
        'variation_option_ids' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationOptions(): BelongsToMany
    {
        return $this->belongsToMany(
            VariationOption::class,
            'product_variation_set_variation_option',
            'product_variation_set_id',
            'variation_option_id'
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

    protected static function booted()
    {
        static::saved(function ($variationSet) {
            if (is_array($variationSet->variation_option_ids)) {
                $variationSet->variationOptions()->sync($variationSet->variation_option_ids);
            }
        });
    }
}


