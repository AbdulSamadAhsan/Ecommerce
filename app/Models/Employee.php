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
        "bank_name",
		"branch_name",	
		"branch_code",	
		"swift_code",
		"is_primary",
        'gender',
             'emergency_contact_name',
                'emergency_contact_number',
                'emergency_contact_relationship',
                'employment_type',
                'probation_period',
                'reporting_time',
                'shift',
               
];
protected $appends = [
    "annual_salary",
    'age',
    "employee_code"

];

public function getEmployeeCodeAttribute(){
    $name =substr($this->user->name, 0, 3);
    $designation=$this->designation;
    $sentence = "John Doe Manager";

$words = explode(' ', $this->designation);
$code = '';

foreach ($words as $word) {
    $code .= strtoupper(substr($word, 0, 3)) . '-';
}

$code = rtrim($code, '-');

return strtoupper($name . '-' . $code."-".$this->id."-".substr($this->gender,0,1));
}
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
  public function salaryPayments()
{
    return $this->hasMany(SalaryPayment::class);
} 

public function payroll()
{
    return $this->hasMany(Payroll::class);
}
public function attendance()
{
    return $this->hasMany(Attendance::class);
}
public function leave()
{
    return $this->hasMany(Leave::class);
}
public function documents()
{
    return $this->hasMany(EmployeeDocument::class, 'employee_id');
}
public function holidays()
{
    return $this->belongsToMany(
        Holiday::class,
        'employee_holiday'
    );
}
}