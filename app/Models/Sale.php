<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
       protected $guarded=[];
           public function customer()
{
    return $this->belongsTo(Customer::class);
}

    public function orderNumber(){
         return $this->belongsTo(Order::class);
    }
}