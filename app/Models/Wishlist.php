<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    //
      protected $guarded=[];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function items(){
  return $this->hasMany(WishlistItem::class);

    }
    
    
    }