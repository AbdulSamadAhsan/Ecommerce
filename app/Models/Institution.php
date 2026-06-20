<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{


 protected $fillable = [
        'name',
        'type',
        'city',
        'country',
        'address',
        'status',
    ];
      public function education()
    {
        return $this->hasMany(Education::class);
    }
}