<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CVs extends Model
{
    protected $table = 'cvs';

    public function job_post_activity()
    {
        return $this->hasMany(JobPostActivity::class);
    }
}
