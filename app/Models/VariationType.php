<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariationType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
    public function variationOptions()
    {
        return $this->hasMany(VariationOption::class);
    }

}
