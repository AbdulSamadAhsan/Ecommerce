<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
     protected $fillable = [
        'employee_id',
        'month',
        'salary_id',
        'allowances',
        'bonus',
        'overtime',
        'deduction',
        'tax',
        'amount',
        'payroll_id',
        'payment_method',
        'status',
        'paid_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    // SalaryPayment.php
public function payroll()
{
    return $this->belongsTo(Payroll::class);
}
}