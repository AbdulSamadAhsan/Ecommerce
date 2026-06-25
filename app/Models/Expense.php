<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    
    protected $fillable = [
        'expense_category_id',
        'title',
        'amount',
        'expense_date',
        'description',
        'receipt',
        'payment_method',
        'status',
    ];
    public function expenseCategory()
{
    return $this->belongsTo(ExpenseCategory::class);
}
}