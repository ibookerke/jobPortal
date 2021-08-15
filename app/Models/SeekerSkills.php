<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeekerSkills extends Model
{
    protected $table = 'seeker_skills';

    /**
     * Get all of the skills .
     */
    public function skills()
    {
        return $this->morphedByMany(Skills::class, 'skills');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function cvs()
    {
        return $this->morphedByMany(CVs::class, 'cvs');
    }
}
