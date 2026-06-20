<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;
class Category extends Model
{
     protected $fillable = [
        'name',
        'status'
    ];

public function products()
{
    return $this->hasMany(Product::class);
}
  public function brands(): BelongsToMany
    {
        return $this->belongsToMany(
            Brand::class,
            'products',
            'category_id',
            'brand_id'
        )->distinct();
    }
}