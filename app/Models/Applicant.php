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
public function educations()
{
    return $this->hasMany(
        CandidateEducation::class,
        'job_application_id'
    );
}

public function works()
{
    return $this->hasMany(
        CandidateWork::class,
        'job_application_id'
    );
}

public function documents()
{
    return $this->hasMany(
        CandidateDocument::class,
        'job_application_id'
    );
}
}