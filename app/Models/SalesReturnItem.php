<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesReturnItem extends Model
{
    //
      protected $guarded=[];
      // SalesReturnItem.php
public function product()
{
    return $this->belongsTo(Product::class);
}
}