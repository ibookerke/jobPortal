<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $table = 'job_post';

    public function job_location()
    {
        return $this->belongsTo(JobLocation::class, 'location_id');
    }

    public function companies()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function job_type()
    {
        return $this->belongsToMany(JobType::class);
    }
}
