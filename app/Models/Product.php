<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{



 protected $appends=[
    "profit",
    "Badge",
    
         'discount_amount',
        'price_after_discount',
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
    'brand_id',
    "discount",
    "stock"
];
public function getBadgeAttribute(){
 $quantity= $this->salesitem->sum("quantity");
 if($quantity==0){
    
 }
}

  public function getDiscountAmountAttribute()
    {
        return ceil(($this->discount / 100) * $this->selling_price);
    }

    public function getPriceAfterDiscountAttribute()
    {
        return $this->selling_price - $this->discount_amount;
    }

public function getProfitAttribute(){
   return $this->price_after_discount - $this->purchase_price;
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
public function salesreturnitem()
{
    return $this->hasMany(SalesReturnItem::class);
}



public function purchasesitem()
{
    return $this->hasMany(PurchaseItem::class);
}
public function purchasereturnitem()
{
    return $this->hasMany(PurchaseReturnItem::class);
}

public function stockmovement()
{
    return $this->hasMany(StockMovement::class);
}
public function salesstatus(){
$quantity_sold= $this->salesitem()->sum("quantity");   
if($quantity_sold > 5){

}else{

}
}
public function stocks(){
       return $this->hasOne(Stock::class);
}
public function getStockAttribute()
{
    return $this->stocks?->quantity ?? 0;
}
}