<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    //
      protected $guarded=[];
      public function documents()
{
    return $this->hasMany(CandidateDocument::class, 'job_application_id');
}

      public function works()
{
    return $this->hasMany(CandidateWork::class, 'job_application_id');
}
      public function educations()
{
    return $this->hasMany(CandidateEducation::class, 'job_application_id');
}

}