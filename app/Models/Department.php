<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    protected $guarded=[];   
  public function designations()
    {
        return $this->hasMany(Designation::class);
    }
      public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
    public function job(){
        return $this->hasMany(JobPosting::class);
    }
    public function applications()
{
    return $this->hasManyThrough(
        JobApplication::class,
        JobPosting::class,
        'department_id',   // FK on job_postings
        'job_posting_id',  // FK on job_applications
        'id',              // PK on departments
        'id'               // PK on job_postings
    );
}
}