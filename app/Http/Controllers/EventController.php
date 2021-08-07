<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller{

    public function create_event(Request $request) {
        try{

        }
        catch (\Exception $e){
            return response()->json(["status" => "error", "message" =>  $e->getMessage()."<br>".$e->getFile()." LINE:".$e->getLine()], 400);
        }
    }

}
