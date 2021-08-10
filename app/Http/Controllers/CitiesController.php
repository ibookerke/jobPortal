<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function getCitiesByState(Request $request): array
    {
        return (new \App\Models\Cities)->getCitiesByState($request['state_id']);
    }
}
