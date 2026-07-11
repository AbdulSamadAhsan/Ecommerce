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



    public function deliveryBoy()
    {
        return $this->hasOneThrough(
            DeliveryBoy::class,
            DeliveryAssignment::class,
            'shipment_id',       // FK on delivery_assignments table
            'id',                // FK on delivery_boys table
            'id',                // Local key on shipments table
            'delivery_boy_id'    // Local key on delivery_assignments table
        );
    }


  
}