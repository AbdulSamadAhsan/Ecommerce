<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    // Leave.php
     protected $guarded=[];
public function employee()
{
    return $this->belongsTo(Employee::class);
}

public function approvedBy()
{
    return $this->belongsTo(User::class, 'approved_by');
}
}