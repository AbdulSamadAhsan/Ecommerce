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
        public function invoice()
    {
        return $this->hasOne(Invoice::class, 'sale_id', 'sale_id');
    }
       public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,
            Sale::class,
            'id',          // Local key on sales
            'id',          // Local key on customers
            'sale_id',     // Foreign key on orders
            'customer_id'  // Foreign key on sales
        );
    }
}