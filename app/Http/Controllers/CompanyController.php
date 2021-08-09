<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    private $create_rules = [
        'company_name' => "required|min:2|max:100",
        'user_id' => "required|numeric|min:1",
        'profile_description' => "required|max:1000",
        'business_stream_id' => "required|numeric|min:1",
        'establishment_date' => "date_format:Y-m-d|before:today",
        'company_website_url' => 'max:500|nullable|max:500|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'image' => 'min:2|max:255'
    ];

    private $update_rules = [
        'company_name' => "min:2|max:100",
        'user_id' => "required|numeric|min:1",
        'profile_description' => "nullable|max:1000",
        'business_stream_id' => "numeric|min:1",
        'establishment_date' => "min:10|max:10|date_format:Y-m-d|before:today",
        'company_website_url' => 'min:2|max:500|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'image' => 'min:2|max:255'
    ];

    public function createCompany(Request $request) {
        try{
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE){
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            //checking if the data passed satisfies the validation requirements
            $validator = Validator::make($request->all(), $this->create_rules);
            if($validator->fails()){
                return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
            }

            $company = new Company();
            $company->company_name = $content->company_name;
            $company->user_id = $content->user_id;
            $company->profile_description = $content->profile_description;
            $company->business_stream_id = $content->business_stream_id;

            if(property_exists($content, "establishment_date")){
                $company->establishment_date = $content->establishment_date;
            }
            if(property_exists($content, "company_website_url")){
                $company->company_website_url = $content->company_website_url;
            }
            if(property_exists($content, "image")){
                $company->image = $content->image;
            }
            $company->save();

            return response()->json(["status" => "success", "message" => "Компания успешно создана"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function updateCompany(Request $request) {
        try{
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE){
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            //checking if the data passed satisfies the validation requirements
            $validator = Validator::make($request->all(), $this->update_rules);
            if($validator->fails()){
                return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
            }

            //getting an existing user
            $company = Company::where("user_id", "=", $content->user_id)
                ->first();

            //check if there is a company
            if(is_null($company)) {
                return response()->json(["status" => "error", "message" => "Компания соответсвтующая пользователю не найдена"], 400);
            }

            if(property_exists($content, "company_name")){
                $company->company_name = $content->company_name;
            }

            if(property_exists($content, "profile_description")){
                $company->profile_description = $content->profile_description;
            }

            if(property_exists($content, "business_stream_id")){
                $company->business_stream_id = $content->business_stream_id;
            }

            if(property_exists($content, "establishment_date")){
                $company->establishment_date = $content->establishment_date;
            }
            if(property_exists($content, "company_website_url")){
                $company->company_website_url = $content->company_website_url;
            }
            if(property_exists($content, "image")){
                $company->image = $content->image;
            }

            $company->save();

            return response()->json(["status" => "success", "message" => "Данные обновлены"], 200);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }



    public function getCompany($user_id) {
        $company = Company::where("user_id", "=", $user_id)
            ->first();

        return response()->json(["status" => "success", "company" => $company], 200);
    }
}
