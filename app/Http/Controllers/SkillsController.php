<?php

namespace App\Http\Controllers;

use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SkillsController extends Controller
{
    public function getSkills(Request $request)
    {
        try
        {
            return Skills::limit(30)->get();
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function searchSkills(Request $request)
    {
        try
        {
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }
            $search = $content->search;

            return Skills::where('name', 'LIKE', "{$search}%")->limit(30)->get();
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function insertSkills($skills)
    {
        try
        {
            $array = array();
            foreach ($skills as $skill) {
                array_push($array, Skills::insertGetId($skill));
            }
            return $array;
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }
}
