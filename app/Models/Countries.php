<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'countries';

    public function getCountries(): array
    {
        return $this::select('id', 'name')->get()->toArray();
    }
}
