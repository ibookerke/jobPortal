<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function get_cities() {
        return Cities::all();
    }

    public function valid_check($object, $prop) {
        if(trim($object->$prop) == "" || $object->$prop == null){
            return false;
        }
        else{
            return true;
        }
    }

}
