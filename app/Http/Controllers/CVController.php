<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CVs;
use App\Models\Education;
use App\Models\Experience;
use App\Models\JobPostActivity;
use App\Models\SeekerSkills;
use App\Models\Skills;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use stdClass;

class CVController extends Controller
{
    private $create_rules = [
        'experience' => [
            'user_id' => "required|numeric|min:1",
            'cv_id' => "required|numeric|min:1",
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
            'name' => "required|max:255"
        ]
    ];

    public function getSeekerCVs(Request $request) {
        try
        {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            $user_id = $content->user_id;
            $data = array();

            $cvs = CVs::where('user_id', '=', $user_id)->get();
            foreach ($cvs as $cv)
            {
                $cv_id = $cv['id'];
                $educations = Education::where('cv_id', '=', $cv_id)->get();
                $experiences = Experience::where('cv_id', '=', $cv_id)->get();
                $skills = SeekerSkills::where('cv_id', '=', $cv_id)
                    ->join('skills', 'seeker_skills.skill_id', '=', 'skills.id')
                    ->select('skills.*')
                    ->get();

                array_push($data, [
                        'personalInformation' => $cv,
                        'educationArray' => $educations,
                        'experienceArray' => $experiences,
                        'skillArray' => $skills
                    ]
                );
            }

            return $data;
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function getCvs(){
        try{
            $data = CVs::get();
            $education = Education::get();
            $experience = Experience::get();
            $skills = DB::table('seeker_skills')
                ->join('skills', 'seeker_skills.skill_id', '=', 'skills.id')
                ->select('skills.*', "seeker_skills.cv_id")
                ->get();


            foreach ($data as $cv){
                $cv_edu = [];
                foreach ($education as $edu){
                    if($edu->cv_id == $cv->id){
                        array_push($cv_edu, $edu);
                    }
                }
                $cv_exp = [];
                foreach ($experience as $exp){
                    if($exp->cv_id == $cv->id){
                        array_push($cv_exp, $exp);
                    }
                }
                $cv_skills = [];
                foreach ($skills as $sk){
                    if($sk->cv_id == $cv->id){
                        array_push($cv_skills, $sk);
                    }
                }

                $cv->educations = $cv_edu;
                $cv->experiences = $cv_exp;
                $cv->skills = $cv_skills;
            }
            return $data;
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function createCV(Request $request)
    {
        try {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            $cv = $content->cv;
            $personal_information = $cv->personalInformation;
            $experience_array = $cv->experienceArray;
            $education_array = $cv->educationArray;
            $skill_array = $cv->skillArray;

            //checking if the data passed satisfies the validation requirements
            if (trim($personal_information->date_of_birth) == null)
            {
                $personal_information->date_of_birth = null;
            }
            $eligible_for_work_age = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 14));
            $validator = Validator::make((array)$personal_information, [
                'user_id' => "required|numeric|min:1",
                'job_title' => "required|min:2|max:255",
                'firstname' => "required|min:2|max:255",
                'lastname' => "required|min:2|max:255",
                'date_of_birth' => "nullable|date_format:Y-m-d|before:" . $eligible_for_work_age,
                'gender' => "nullable|numeric|min:0",
                'phone' => "nullable|min:2|max:20",
                'salary' => "nullable|min:2|max:255"
            ]);
            if ($validator->fails())
            {
                return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
            }
            $cv_id = CVs::insertGetId((array)$personal_information);

            foreach ($experience_array as $experience)
            {
                $experience->cv_id = $cv_id;
                $validator = Validator::make((array)$experience, $this->create_rules['experience']);
                if ($validator->fails())
                {
                    return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                }

                Experience::insert((array)$experience);
            }


            foreach ($education_array as $education)
            {
                $education->cv_id = $cv_id;
                $validator = Validator::make((array)$education, $this->create_rules['education']);
                if ($validator->fails())
                {
                    return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                }

                Education::insert((array)$education);
            }


            $new_skills = array();
            $seeker_skills = array();
            foreach ($skill_array as $skillKey => $skillVal)
            {
                if (gettype($skillVal) == 'string')
                {
                    array_push($new_skills, ['name' => $skillVal]);
                    unset($skill_array[$skillKey]);
                }
                else
                {
                    array_push($seeker_skills, ['skill_id' => $skillVal->id, 'cv_id' => $cv_id]);
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

    public function updateCV(Request $request)
    {
        try
        {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            $cv = $content->cv;
            $personal_information = $cv->personalInformation;
            $cv_id = $personal_information->id;
            $experience_array = $cv->experienceArray;
            $education_array = $cv->educationArray;
            $skill_array = $cv->skillArray;

            //checking if the data passed satisfies the validation requirements
            if (trim($personal_information->date_of_birth) == null)
            {
                $personal_information->date_of_birth = null;
            }
            $eligible_for_work_age = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 14));
            $validator = Validator::make((array)$personal_information, [
                'id' => "required|numeric|min:1",
                'user_id' => "required|numeric|min:1",
                'job_title' => "required|min:2|max:255",
                'firstname' => "required|min:2|max:255",
                'lastname' => "required|min:2|max:255",
                'date_of_birth' => "nullable|date_format:Y-m-d|before:" . $eligible_for_work_age,
                'gender' => "nullable|numeric|min:0",
                'phone' => "nullable|min:2|max:20",
                'salary' => "nullable|min:2|max:255"
            ]);
            if ($validator->fails())
            {
                return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
            }
            CVs::where('id', '=', $cv_id)->update((array)$personal_information);

            Education::where('cv_id', '=', $cv_id)->delete();
            foreach ($education_array as $education)
            {
                $education->cv_id = $cv_id;
                $validator = Validator::make((array)$education, $this->create_rules['education']);
                if ($validator->fails())
                {
                    return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                }

                Education::insert((array)$education);
            }

            Experience::where('cv_id', '=', $cv_id)->delete();
            foreach ($experience_array as $experience)
            {
                $experience->cv_id = $cv_id;
                $validator = Validator::make((array)$experience, $this->create_rules['experience']);
                if ($validator->fails())
                {
                    return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                }

                Experience::insert((array)$experience);
            }

            SeekerSkills::where('cv_id', '=', $cv_id)->delete();
            $new_skills = array();
            $seeker_skills = array();
            foreach ($skill_array as $skillKey => $skillVal)
            {
                if (gettype($skillVal) == 'string')
                {
                    array_push($new_skills, ['name' => $skillVal]);
                    unset($skill_array[$skillKey]);
                }
                else
                {
                    array_push($seeker_skills, ['skill_id' => $skillVal->id, 'cv_id' => $cv_id]);
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

            return response()->json(["status" => "success", "message" => "CV successfully updated"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function deleteCV(Request $request)
    {
        try
        {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            CVs::find($content->cv_id)->delete();

            return response()->json(["status" => "success", "message" => "CV successfully deleted"], 200);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function getSeekerCVsForRespond(Request $request) {
        try
        {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            return CVs::where('user_id', '=', $content->user_id)->get();
        }
        catch (\Exception $e)
        {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function respondToJobPost(Request $request)
    {
        try {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            JobPostActivity::insert((array) $content);

            return response()->json(["status" => "success", "message" => "CV successfully responded to Job Post"], 200);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }
}
