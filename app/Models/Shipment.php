<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public function deliveryassign(){
                     return $this->hasOne(DeliveryAssignment::class);
    }
}