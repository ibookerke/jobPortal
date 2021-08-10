<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobType extends Model
{
    protected $table = 'job_type';

    public function getJobTypeArray(): array
    {
        return $this::all()->toArray();
    }
}
