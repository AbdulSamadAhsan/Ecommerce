<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{



 protected $appends=[
    "profit"
 ];
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

public function getProfitAttribute(){
    return date("d-F-Y",strtotime("+1 month"));
}
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
public function reviews()
{
    return $this->hasMany(Review::class);
}

public function salesitem()
{
    return $this->hasMany(SaleItem::class);
}



public function purchasesitem()
{
    return $this->hasMany(PurchaseItem::class);
}
public function stockmovement()
{
    return $this->hasMany(StockMovement::class);
}

}