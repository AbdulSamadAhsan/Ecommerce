<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAssignment extends Model
{
      public function deliveryboy(){
                     return $this->belongsTo(DeliveryBoy::class,"delivery_boy_id");
    }
}