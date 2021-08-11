<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CVs;
use App\Models\Education;
use App\Models\Experience;
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

            $data = array();
            $user_id = $request['user_id'];
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

                array_push($data,
                    [
                        'cv' => $cv,
                        'educations' => $educations,
                        'experiences' => $experiences,
                        'skills' => $skills
                    ]);
            }

            return $data;
        }
        catch (\Exception $e) {
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
        try
        {

            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if(json_last_error() != JSON_ERROR_NONE)

            return response()->json(["message" => $content], 400);
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


            $cv = $request['cv'];
            $cv_id = $cv['id'];
            $user_id = $cv['user_id'];

            //checking if the data passed satisfies the validation requirements
            if (trim($cv['date_of_birth']) == null)
            {
                $cv['date_of_birth'] = null;
            }
            $eligible_for_work_age = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d"), date("Y") - 14));
            $validator = Validator::make($cv, [
                'user_id' => "required|numeric|min:1",
                'id' => "required|numeric|min:1",
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
            CVs::where('id', '=', $cv_id)->update($cv);


            $removedEducation = $request['removedEd'];
            if (count($removedEducation) > 0)
            {
                foreach ($removedEducation as $remEd)
                {
                    Education::where('id', '=', $remEd)->delete();
                }
            }

            $educationArray = $request['education'];
            foreach ($educationArray as $education)
            {
                if (!array_key_exists('id', $education))
                {
                    $education['cv_id'] = $cv_id;
                    $validator = Validator::make($education, $this->create_rules['education']);
                    if ($validator->fails())
                    {
                        return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                    }
                    Education::insert($education);
                }
            }


            $removedExperience = $request['removedExp'];
            if (count($removedExperience) > 0)
            {
                foreach ($removedExperience as $remExp)
                {
                    Experience::where('id', '=', $remExp)->delete();
                }
            }

            $experienceArray = $request['experience'];
            foreach ($experienceArray as $experience)
            {
                if (!array_key_exists('id', $experience))
                {
                    $experience['cv_id'] = $cv_id;
                    $validator = Validator::make($experience, $this->create_rules['experience']);
                    if ($validator->fails())
                    {
                        return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                    }

                    Experience::insert($experience);
                }
            }


            SeekerSkills::where('cv_id', '=', $cv_id)->delete();
            $skills = $request['skills'];
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

            $cv_id = $request['cv_id'];
            CVs::find($cv_id)->delete();

            return response()->json(["status" => "success", "message" => "CV successfully deleted"], 200);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }
}
