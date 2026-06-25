<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{



 protected $appends=[
    "profit",
    "Badge",
    "Stock"
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
public function getBadgeAttribute(){
 $quantity= $this->salesitem->sum("quantity");
 if($quantity==0){
    
 }
}
public function getProfitAttribute(){
   return $this->selling_price - $this->purchase_price;
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
public function getStockAttribute(){
    return $this->purchasesitem()->sum("quantity") - $this->salesitem()->sum("quantity");
}


public function purchasesitem()
{
    return $this->hasMany(PurchaseItem::class);
}
public function stockmovement()
{
    return $this->hasMany(StockMovement::class);
}
public function salesstatus(){
$quantity_sold= $this->salesitem()->sum("quantity");   
if($quantity_sold > 5){

}
}
}