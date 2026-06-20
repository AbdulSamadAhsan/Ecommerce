<?php

namespace App\Models;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Brand extends Model
{
    protected $fillable = [
    'title',
    'description',
    'logo',
    'status',
    ];
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
     public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            'products',
            'brand_id',
            'category_id'
        )->distinct();
    }

    
}