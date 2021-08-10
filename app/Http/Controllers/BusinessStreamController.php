<?php

namespace App\Http\Controllers;

use App\Models\BusinessStream;
use Illuminate\Http\Request;

class BusinessStreamController extends Controller
{
    public function search_business_stream(Request $request){
        try {
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE) {
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }
            $search = $content->search;

            return BusinessStream::where('business_stream_name', 'LIKE', "{$search}%")->limit(30)->get();
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function get_business_stream(Request $request){
        try {
            return BusinessStream::limit(30)->get();
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

}
