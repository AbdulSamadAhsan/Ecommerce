<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    //
      protected $guarded=[];
      public function jobApplications()
{
    return $this->hasMany(JobApplication::class, 'applicant_id');
}
}