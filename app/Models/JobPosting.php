<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    //
      protected $guarded=[];
      public function department(){
        return $this->belongsTo(Department::class);
      }
      public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
}