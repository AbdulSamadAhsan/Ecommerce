<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{



    protected $guarded = [];
    public function deliveryassign(){
                     return $this->hasOne(DeliveryAssignment::class);
    }
      public function ShippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }
  public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}

  
}