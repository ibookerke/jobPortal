<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocations(Request $request) {
        try{
            //json validation
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE){
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            //check if property exists
            if(!property_exists($content, "column")){
                if($content->column == null && trim($content->column) == "") {
                    return response()->json(["status" => "error", "message" => "Некорректный column"]);
                }
                return response()->json(["status" => "error", "message" => "Отсутсвует column"], 400);
            }
            //setting order
            if(!property_exists($content, "order") || $content->order == null) {
                $order = "ASC";
            }
            else{
                $order = $content->order;
            }

            //setting perPage
            if(!property_exists($content, "perpage") || $content->perpage == null) {
                $perpage = 10;
            }
            else {
                $perpage = $content->perpage;
            }

            //setting page
            if(!property_exists($content, "page") || $content->page == null) {
                $page = 1;
            }
            else{
                $page = $content->page;
            }

            if(strtoupper($order) == "ASC" || "DESC") {
                $data = Location::orderBy($content->column, $order)
                    ->limit($perpage)
                    ->skip($perpage *  ($page -1))
                    ->get();
            }
            else{
                $data = Location::limit($perpage)
                    ->skip($perpage *  ($page -1))
                    ->get();
            }

            return response()->json(["status" => "success", "grid" => $data], 200);
        }
        catch (\Exception $e){
            return response()->json(["status" => "error", "message" =>  $e->getMessage()."<br>".$e->getFile()." LINE:".$e->getLine()], 400);
        }
    }


    public function saveLocation(Request $request) {
        try{
            //getting content and checking if the json is correct
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE){
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            //checking if the properties are set
            if(!property_exists($content, "name")){
                if($content->name == null && trim($content->name) == "") {
                    return response()->json(["status" => "error", "message" => "Некорректное название"]);
                }
                return response()->json(["status" => "error", "message" => "Отсутсвует название"], 400);
            }
            if(!property_exists($content, "address")){
                if($content->address == null && trim($content->address) == "") {
                    return response()->json(["status" => "error", "message" => "Некорректное адрес"]);
                }
                return response()->json(["status" => "error", "message" => "Отсутсвует адрес"], 400);
            }
            if(!property_exists($content, "schema")){
                return response()->json(["status" => "error", "message" => "Отсутсвует schema"], 400);
            }
            if(!property_exists($content, "city_id")){
                if($content->address == null && trim($content->address) == "" || $content->city_id < 1) {
                    return response()->json(["status" => "error", "message" => "Некорректный город"]);
                }
                return response()->json(["status" => "error", "message" => "Отсутсвует город"], 400);
            }

            if(trim($content->schema) != "") {
                $schema = json_decode($content->schema);
                if(json_last_error() != JSON_ERROR_NONE){
                    return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
                }
            }

            $location = new Location();
            $location->name = $content->name;
            $location->address = $content->address;
            $location->schema = $content->schema;
            $location->city_id = $content->city_id;
            $location->save();

            return response()->json(["status" => "success", "message" => "Локация успешно сохранена"], 200);

        }
        catch (\Exception $e){
            return response()->json(["status" => "error", "message" =>  $e->getMessage()."<br>".$e->getFile()." LINE:".$e->getLine()], 400);
        }
    }
}
