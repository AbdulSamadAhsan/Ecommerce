<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded=[];
    public function supplier()
{
    return $this->belongsTo(\App\Models\Supplier::class);
}

public function items()
{
    return $this->hasMany(\App\Models\PurchaseItem::class);
}
}