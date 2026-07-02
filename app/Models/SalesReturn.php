<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturn extends Model
{
    //
      protected $guarded=[];
      // SalesReturn.php
public function items()
{
    return $this->hasMany(SalesReturnItem::class, 'sales_return_id');
}
public function item()
{
    return $this->hasOne(SalesReturnItem::class, 'sales_return_id');
}
public function sale()
{
    return $this->belongsTo(Sale::class, 'sale_id');
}

public function order()
{
    return $this->hasOne(Order::class, 'sale_id', 'sale_id');
}

public function customer()
{
    return $this->hasOneThrough(
        Customer::class,
        Sale::class,
        'id',
        'id',
        'sale_id',
        'customer_id'
    );
}


}