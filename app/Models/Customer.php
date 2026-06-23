<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Customer extends Authenticatable
{
      protected $fillable = [
        'phone',
        'status',
        'user_id'
    ];
     public function user()
{
    return $this->belongsTo(User::class);
}
}