<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function getJobTypeArray(): array
    {
        return (new \App\Models\JobType)->getJobTypeArray();
    }
}
