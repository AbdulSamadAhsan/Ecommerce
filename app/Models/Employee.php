<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{



protected $fillable = [
    'user_id',
    'institution_id',
    'education_id',
    'department_id',
    'phone',
    'designation',
    'joining_date',
    'address',
    'cnic',
    'photo',
    'status',
    'salary',
    'father_name',
    'date_of_birth',
];
    public function user()
{
    return $this->belongsTo(User::class);
}
     public function department(){
        return $this->belongsTo(Department::class);
     }
     public function education(){
           return $this->belongsTo(Education::class);
     }
     public function institute(){
          return $this->belongsTo(Institution::class);
     }
}