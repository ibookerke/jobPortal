<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public static function getCountries(): array
    {
        return (new \App\Models\Countries)->getCountries();
    }
}
