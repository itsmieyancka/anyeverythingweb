<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'department_id',
        'is_active',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            // Auto-generate slug from name if not manually provided
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Parent categories relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Subcategories relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Products under this categories
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Associated department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
