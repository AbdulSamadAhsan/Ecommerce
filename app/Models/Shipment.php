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
    public function getStatusAttribute($value){
       if($value=="out_for_delivery"){
        return "Out For Delivery";
       }elseif($value=="in_transit"){
        return "On The Way";
       }else{
        return ucfirst($value);
       }

    }
}