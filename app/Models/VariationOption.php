<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class VariationOption extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['variation_type_id', 'value'];

    public function variationType()
    {
        return $this->belongsTo(VariationType::class);
    }

    public function variationSets()
    {
        return $this->belongsToMany(ProductVariationSet::class,
        'product_variation_set_variation_option');
    }
}
