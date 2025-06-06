<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
protected $fillable = ['user_id', 'business_name', 'description', 'phone', 'address','commission_rate'];

public function user(): BelongsTo
{
return $this->belongsTo(User::class);
}

public function products(): HasMany
{
return $this->hasMany(Product::class);
}
}
