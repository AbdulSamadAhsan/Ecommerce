<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    //
      protected $guarded=[];
         public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designations()
{
    return $this->hasMany(Designation::class);
}
    public function jobPostings()
{
    return $this->hasOne(JobPosting::class);
}
    public function jobApplications()
    {
        return $this->hasManyThrough(
            JobApplication::class, // Final model
            JobPosting::class,     // Intermediate model
            'designation_id',      // FK on job_postings
            'job_posting_id',      // FK on job_applications
            'id',                  // PK on designations
            'id'                   // PK on job_postings
        );
    }


}