<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
 protected $fillable = [
    'warehouse_id',
    'supplier_id',
    'category_id',
    'name',
    'sku',
    'purchase_price',
    'selling_price',
    'quantity',
    'minimum_stock',
    'description',
    'image',
    'status',
    'brand_id'
];   
public function category()
{
    return $this->belongsTo(Category::class);
}

public function supplier()
{
    return $this->belongsTo(Supplier::class);
}


public function brand()
{
    return $this->belongsTo(Brand::class);
}
public function warehouse()
{
    return $this->belongsTo(Warehouse::class);
}
}