<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CVs;
use App\Models\Education;
use App\Models\Experience;
use App\Models\SeekerSkills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CVController extends Controller
{
    private $create_rules = [
        'experience' => [
            'user_id' => "required|numeric|min:1",
            'cv_id' => "nullable|numeric|min:1",
            'is_current_job' => "required|numeric|min:0",
            'start_date' => "required|date|date_format:Y-m-d|before:today",
            'end_date' => "nullable|date|date_format:Y-m-d|after:start_date|before:today",
            'job_title' => "required|min:2|max:255",
            'company_name' => "required|min:2|max:255",
            'job_description' => "required|min:2|max:4000"
        ],
        'education' => [
            'user_id' => "required|numeric|min:1",
            'cv_id' => "required|numeric|min:1",
            'certificate_degree_name' => "required|min:2|max:255",
            'major' => "nullable|min:2|max:255",
            'starting_date' => "required|date_format:Y-m-d",
            'completing_date' => "required|date_format:Y-m-d",
            'percentage' => "nullable|min:1|max:255",
            'cgpa' => "nullable|min:1|max:255"
        ],
        'skills' => [
            'name' => "required|min:2|max:255"
        ]
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

    public function createCV(Request $request)
    {
        try
        {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            $cv = $request['cv'];
            $experiences = $request['experience'];
            $educations = $request['education'];
            $skills = $request['skills'];

            //checking if the data passed satisfies the validation requirements
            if (trim($cv['date_of_birth']) == null)
            {
                $cv['date_of_birth'] = null;
            }
            $eligible_for_work_age = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 14));
            $validator = Validator::make($cv, [
                'user_id' => "required|numeric|min:1",
                'job_title' => "required|min:2|max:255",
                'firstname' => "required|min:2|max:255",
                'lastname' => "required|min:2|max:255",
                'date_of_birth' => "nullable|date_format:Y-m-d|before:" . $eligible_for_work_age,
                'gender' => "nullable|numeric|min:0",
                'phone' => "nullable|min:2|max:20",
                'salary' => "nullable|min:2|max:255",
                'currency' => "nullable|min:2|max:50"
            ]);
            if ($validator->fails())
            {
                return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
            }
            $cv_id = CVs::insertGetId($cv);

//            return $educations;

//            return $experiences;
            foreach ($experiences as $experience)
            {
                $experience['cv_id'] = $cv_id;
                $validator = Validator::make($experience, $this->create_rules['experience']);
                if ($validator->fails())
                {
                    return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                }

                Experience::insert($experience);
            }

            foreach ($educations as $education)
            {
                $education['cv_id'] = $cv_id;
                $validator = Validator::make($education, $this->create_rules['education']);
                if ($validator->fails())
                {
                    return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                }

                Education::insert($education);
            }

            $new_skills = array();
            $seeker_skills= array();
            foreach ($skills as $skillKey => $skillVal)
            {
                if (gettype($skillVal) == 'string')
                {
                    array_push($new_skills, ['name' => $skillVal]);
                    unset($skills[$skillKey]);
                }
                else
                {
                    array_push($seeker_skills, ['skill_id' => $skillVal['id'], 'cv_id' => $cv_id]);
                }
            }

            if (count($new_skills) > 0)
            {
                foreach ($new_skills as $new_skill) {
                    $validator = Validator::make($new_skill, $this->create_rules['skills']);
                    if ($validator->fails())
                    {
                        return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                    }
                }

                $new_skills_id = (new SkillsController)->insertSkills($new_skills);
                foreach ($new_skills_id as $val) {
                    array_push($seeker_skills, ['skill_id' => $val, 'cv_id' => $cv_id]);
                }
            }

            SeekerSkills::insert($seeker_skills);

            return response()->json(["status" => "success", "message" => "CV successfully inserted"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }
}
