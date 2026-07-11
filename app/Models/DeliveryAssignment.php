<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAssignment extends Model
{
      protected $guarded=[];
      public function deliveryboy(){
                     return $this->belongsTo(DeliveryBoy::class,"delivery_boy_id");
    }
      public function order()
    {
        return $this->hasOneThrough(
            Order::class,
            Shipment::class,
            'id',          // shipment primary key
            'id',          // order primary key
            'shipment_id', // delivery_assignments shipment_id
            'order_id'     // shipments order_id
        );
    }

    public function shipment()
    {
        return $this->belongsTo(
            Shipment::class,
            'shipment_id'
        );
    }

    

}