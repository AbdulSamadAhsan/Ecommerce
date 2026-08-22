<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewFeedback extends Model
{
    //
      protected $guarded=[];
         public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}