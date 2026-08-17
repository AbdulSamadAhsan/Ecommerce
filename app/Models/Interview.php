<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    //
      protected $guarded=[];
       protected $casts = [
        'scheduled_at' => 'datetime',
    ];
     public function jobApplication()
    {
        return $this->belongsTo(
            JobApplication::class,
            'job_application_id'
        );
    }

    public function jobPosting()
    {
        return $this->hasOneThrough(
            JobPosting::class,
            JobApplication::class,
            'id',              // FK on job_applications for Interview
            'id',              // FK on job_postings
            'job_application_id',
            'job_posting_id'
        );
    }
    public function applicant()
{
    return $this->belongsTo(Applicant::class);
}
public function interviewer()
{
    return $this->belongsTo(User::class, 'interviewer_id');
}
}