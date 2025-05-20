<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariationOption extends Model
{
    use HasFactory;

    protected $fillable = ['variation_type_id', 'value'];

    public function variationType()
    {
        return $this->belongsTo(VariationType::class);
    }

    public function variationSets()
    {
        return $this->belongsToMany(ProductVariationSet::class);
    }
}
