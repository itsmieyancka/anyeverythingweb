<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variationSets(): HasMany
    {
        return $this->hasMany(ProductVariationSet::class);
    }

    public function variationTypes(): HasMany
    {
        return $this->hasMany(VariationType::class);

    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('public');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(500)
            ->height(500)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('small')
            ->width(700)
            ->height(700)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(1000)
            ->height(1000)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('large')
            ->width(1500)
            ->height(1500)
            ->sharpen(10)
            ->nonQueued();
    }

    public function getRelations(): array
    {
        return [
            \App\Filament\Resources\ProductResource\RelationManagers\VariationTypesRelationManager::class,
        ];
    }
}
