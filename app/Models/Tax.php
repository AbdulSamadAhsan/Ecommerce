<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $guarded = [];

    protected $casts = [
        'rate' => 'decimal:2',
        'status' => 'boolean',
    ];
}