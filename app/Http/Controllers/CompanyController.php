<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyBusinessStream;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    private $create_rules = [
        'company_name' => "required|min:2|max:100",
        'user_id' => "required|numeric|min:1|unique:companies",
        'profile_description' => "required|min:2",
        'establishment_date' => "nullable|date_format:Y-m-d|before:today",
        'company_website_url' => 'nullable|min:2|max:500|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'image' => 'nullable|min:2|max:255',

    ];

    private $update_rules = [
        'company_name' => "min:2|max:100",
        'user_id' => "required|numeric|min:1",
        'profile_description' => "min:2",
        'establishment_date' => "nullable|min:10|max:10|date_format:Y-m-d|before:today",
        'company_website_url' => 'nullable|min:2|max:500|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'image' => 'nullable|min:2|max:255',
        'new_business' => "nullable|string"
    ];

    private $company_business_stream_rules = [
        'company_id' => "required|numeric|min:1",
        'business_stream_id' => "required|numeric|min:1"
    ];
//'avatar' => 'nullable|max:10240|mimes:jpg,bmp,png'

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
                return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
            }

            $company["company_name"] = $content->company_name;
            $company["user_id"] = $content->user_id;
            $company["profile_description"] = $content->profile_description;


            if(property_exists($content, "establishment_date")){
                $company["establishment_date"] = $content->establishment_date;
            }
            else{
                $company["establishment_date"] = null;
            }


            if(property_exists($content, "company_website_url")){
                $company["company_website_url"] = $content->company_website_url;
            }
            else{
                $company["company_website_url"] = null;
            }


            if(property_exists($content, "image")){
                $company["image"] = $content->image;
            }
            else{
                $company["image"] = null;
            }


            $company_id = Company::insertGetId($company);


            foreach ($content->business_stream as $record){
                $new_record["business_stream_id"] = $record->id;
                $new_record["company_id"] = $company_id;

                $vali = Validator::make($new_record, $this->company_business_stream_rules);

                if($vali->fails()){
                    return response()->json(["status" => "error", "message" => $vali->errors()]);
                }

                CompanyBusinessStream::insert($new_record);
            }


            return response()->json(["status" => "success", "message" => "Компания успешно создана"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function uploadAvatar (Request $request) {
        try{
            if ($request->user_id){
                if($request->hasFile('avatar')){
                    $image = $request->file('avatar');
                    $image_name = $image->getClientOriginalName();

                    $path =  $request->user_id . "_" . $image_name;

                    $image->storeAs('/public/', $path);

                    $update["image"] = $image_name;

                    Company::where("user_id", "=", $request->user_id)->update($update);
                    return response()->json(["status" => "success"], 200);
                }
                else{
                    return response()->json(["status" => "error", "img is absent"], 400);
                }
            }
            else{
                return response()->json(["status" => "error", "message" => "При сохраненнии фотографии возникла ошибка"], 400);
            }
        }
        catch (\Exception $e){
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
                return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
            }

            $company["company_name"] = $content->company_name;
            $company["user_id"] = $content->user_id;
            $company["profile_description"] = $content->profile_description;

            if(property_exists($content, "establishment_date")){
                $company["establishment_date"] = $content->establishment_date;
            }
            else{
                $company["establishment_date"] = null;
            }

            if(property_exists($content, "company_website_url")){
                $company["company_website_url"] = $content->company_website_url;
            }
            else{
                $company["company_website_url"] = null;
            }

            if(property_exists($content, "image")){
                $company["image"] = $content->image;
            }
            else{
                $company["image"] = null;
            }

            Company::where('id', "=", $content->id)->update($company);

            $removed_business = $content->removed_business;

            if(count($removed_business) > 0){
                foreach ($removed_business as $record) {
                    if(!property_exists($record, "cbs")){
                        return response()->json(["status" => "error", "message" => "неправильный removed_business"], 400);
                    }
                    else{
                        CompanyBusinessStream::where("id", "=", $record->cbs)->delete();
                    }
                }
            }

            foreach ($content->business_stream as $record){
                $new_record["business_stream_id"] = $record->id;
                $new_record["company_id"] = $content->id;

                $vali = Validator::make($new_record, $this->company_business_stream_rules);

                if($vali->fails()){
                    return response()->json(["status" => "error", "message" => $vali->errors()]);
                }

                CompanyBusinessStream::insert($new_record);
            }


            return response()->json(["status" => "success", "message" => "Компания успешно создана"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }



    public function getCompany($user_id) {
        $company = Company::where("user_id", "=", $user_id)
            ->first();

        if(!is_null($company)){
            $data = DB::table('company_business_stream')
                ->join('business_stream', "company_business_stream.business_stream_id", "=", "business_stream.id")
                ->join("companies", "company_business_stream.company_id", "=", "companies.id")
                ->select('business_stream.id', 'business_stream_name', "company_business_stream.id as cbs")
                ->where("company_business_stream.company_id","=", $company->id)
                ->get();

            return response()->json(["status" => "success", "company" => $company, "business_stream" => $data], 200);

        }
        else{
            return response()->json(["status" => "success", "company" => null, "business_stream" => null], 200);
        }

    }
}
