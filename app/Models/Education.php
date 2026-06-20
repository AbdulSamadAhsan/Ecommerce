<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table="educations";
    protected $fillable = [
        'institution_id',
        'name',
        'short_code',
     
        'status',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}