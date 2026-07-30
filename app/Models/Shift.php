<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    //
      protected $guarded=[];
      public function employees()
{
    return $this->hasMany(Employee::class, 'shift', 'name');
}
}