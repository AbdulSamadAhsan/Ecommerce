<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
    'user_id',
    'company_name',
    'email',
    'phone',
   
    'address',
    'opening_balance',
    'status',
];
   public function user()
{
    return $this->belongsTo(User::class);
}
public function products(){

return $this->hasMany(Product::class);
}
public function payments(){
    return $this->hasMany(SupplierPayment::class);
}
public function purchases(){
    return $this->hasMany(Purchase::class);
}
}