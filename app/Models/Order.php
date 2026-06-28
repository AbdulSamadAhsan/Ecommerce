<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
       protected $guarded=[];

       public function shipment(){
                     return $this->hasOne(Shipment::class);
       }
       public function sale(){
              return $this->belongsTo(Sale::class);
       }
}