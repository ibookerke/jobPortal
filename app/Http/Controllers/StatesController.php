<?php

namespace App\Http\Controllers;

use App\Models\States;
use Illuminate\Http\Request;

class StatesController extends Controller
{
    public static function getStatesByCountry(Request $request):array
    {
        $country_id = $request['country_id'];
        return (new \App\Models\States)->getStatesByCountry($country_id);
    }
}
