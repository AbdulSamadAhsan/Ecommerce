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
    "account_title",	
		"account_number",
		"iban",
		"branch_name",	
		"branch_code",	
		"swift_code",
		"is_primary",
];
protected $appends = [
    "annual_salary",
    'age'
];

public function getAgeAttribute()
{
    return \Carbon\Carbon::parse($this->date_of_birth)->age;
}

    public function user()
{
    return $this->belongsTo(User::class);
}
     public function department(){
        return $this->belongsTo(Department::class);
     }
  public function education()
{
    return $this->belongsTo(Education::class, 'education_id');
}
    public function institute()
{
    return $this->belongsTo(Institution::class, 'institution_id');
}
   public function salaryData(){
     return $this->hasOne(Salary::class, 'employee_id');   
   }
   public function getAnnualSalaryAttribute(){
     return $this->salaryData->net_salary*12;
   }
   
}