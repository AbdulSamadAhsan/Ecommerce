<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
     protected $guarded=[];
     public function payments()
{
    return $this->hasMany(SalaryPayment::class);
}

    public function employee(){
        return $this->belongsTo(Employee::class);
    }


}