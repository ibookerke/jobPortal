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
        'profile_description' => "required",
        'establishment_date' => "nullable|date_format:Y-m-d|before:today",
        'company_website_url' => 'nullable|max:500|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
        'image' => 'nullable|min:2|max:255'
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

            $user = User::where("id", "=", $content->user_id)->first();
            if($user->user_type_id !== 1) {
                return response()->json(["statue" => "error", "message" => "Только работодатель может созадвать компанию"]);
            }

            $company = new Company();
            $company->company_name = $content->company_name;
            $company->user_id = $content->user_id;
            $company->profile_description = $content->profile_description;

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
            //checking if the data passed satisfies the validation requirements
            $validator = Validator::make($request->all(), $this->update_rules);
            if($validator->fails()){
                return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
            }

            //getting an existing user
            $company = Company::where("user_id", "=", $request->user_id)
                ->first();

            //check if there is a company
            if(is_null($company)) {
                return response()->json(["status" => "error", "message" => "Компания соответсвтующая пользователю не найдена"], 400);
            }

            if($this->valid_check($request, "company_name")){
                $company->image = $request->company_name;
            }

            if($this->valid_check($request, "profile_description")){
                $company->profile_description = $request->profile_description;
            }

            if($this->valid_check($request, "establishment_date")){
                $company->establishment_date = $request->establishment_date;
            } else{
                $company->establishment_date = null;
            }

            if($this->valid_check($request, "company_website_url")){
                $company->company_website_url = $request->company_website_url;
            } else{
                $company->company_website_url = null;
            }

            if($this->valid_check($request, "image")){
                $company->image = $request->image;
            }else{
                $company->image = null;
            }

            if($request->hasFile('avatar')){
                $image = $request->file('avatar');
                $image_name = $request->user_id . "_" . $image->getClientOriginalName();

                $image->storeAs('/public/', $image_name);
            }

            $removed_business = json_decode($request->removed_business);
            $new_business = json_decode($request->new_business);

            if(json_last_error() != JSON_ERROR_NONE) {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

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

            foreach ($new_business as $record) {
                if(!property_exists($record, "id")){
                    return response()->json(["status" => "error", "message" => "неправильный removed_business"], 400);
                }
                else{
                    $new_record["company_id"] = $company->id;
                    $new_record["business_stream_id"] = $record->id;

                    $val = Validator::make($new_record, $this->company_business_stream_rules);
                    if($val->fails()){
                        return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                    }
                    CompanyBusinessStream::insert($new_record);
                }
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


        $data = DB::table('company_business_stream')
            ->join('business_stream', "company_business_stream.business_stream_id", "=", "business_stream.id")
            ->join("companies", "company_business_stream.company_id", "=", "companies.id")
            ->select('business_stream.id', 'business_stream_name', "company_business_stream.id as cbs")
            ->get();


        return response()->json(["status" => "success", "company" => $company, "business_stream" => $data], 200);
    }
}
