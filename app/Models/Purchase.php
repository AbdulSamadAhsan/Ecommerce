<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded=[];
    public function supplier()
{
    return $this->belongsTo(\App\Models\Supplier::class);
}

public function items()
{
    return $this->hasMany(\App\Models\PurchaseItem::class);
}

public function product()
{
    return $this->hasManyThrough(
        Product::class,
        PurchaseItem::class,
        'purchase_id', // Foreign key on purchase_items
        'id',          // Foreign key on products
        'id',          // Local key on purchases
        'product_id'   // Local key on purchase_items
    );
}
}