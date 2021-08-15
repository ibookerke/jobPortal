<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';

    public function getCitiesByState($state_id): array
    {
        return $this::where('state_id', '=', $state_id)->select('id', 'name')->get()->toArray();
    }
}
