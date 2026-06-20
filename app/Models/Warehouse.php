<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable=[
"name",'code','manager_id',"phone","address","status"

    ];
}