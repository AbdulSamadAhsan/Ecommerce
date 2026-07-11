<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model
{
     protected $guarded=[];

     public function supplier(){
          return $this->belongsTo(Supplier::class);
     }
     
     public function purchase(){
          return $this->belongsTo(Purchase::class);
     }
}