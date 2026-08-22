<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    //
      protected $guarded=[];
      public function employees()
{
    return $this->belongsToMany(
        Employee::class,
        'employee_holiday'
    );
}
      public function shifts()
{
    return $this->belongsToMany(
        Shift::class,
        'shift_holiday'
    );
}
public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}