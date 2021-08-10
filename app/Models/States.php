<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = 'states';

    public function getStatesByCountry($country_id): array
    {
        return $this::where('country_id', '=', $country_id)->select('id', 'name')->get()->toArray();
    }
}
