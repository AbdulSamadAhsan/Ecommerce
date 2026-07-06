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
    public function warehouse()
{
    return $this->hasOneThrough(
        Warehouse::class,
        Product::class,
        'id',              // products.id
        'id',              // warehouses.id
        'id',              // purchases.id
        'warehouse_id'     // products.warehouse_id
    )
    ->join(
        'purchase_items',
        'purchase_items.product_id',
        '=',
        'products.id'
    )
    ->whereColumn(
        'purchase_items.purchase_id',
        'purchases.id'
    );
}
}