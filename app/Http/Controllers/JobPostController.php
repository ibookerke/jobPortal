<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Company;
use App\Models\Countries;
use App\Models\JobLocation;
use App\Models\JobPost;
use App\Models\JobPostJobType;
use App\Models\JobPostSkills;
use App\Models\JobType;
use App\Models\States;
use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobPostController extends Controller
{
    private $rules = [
        'job_post' => [
            'user_id' => "required|numeric|min:1",
            'company_id' => "required|numeric|min:1",
            'location_id' => "required|numeric|min:1",
            'is_active' => "required|numeric",
            'work_experience_type' => "required|numeric|min:1|max:4",
            'job_description' => "required|min:2",
            'job_title' => "required|min:2|max:255",
            'salary' => 'nullable|numeric'
        ],
        'job_location' => [
            'address' => "required|min:2|max:255",
            'city_id' => "required|numeric|min:1",
            'state_id' => "required|numeric|min:1",
            'country_id' => "required|numeric|min:1"
        ],
        'skills' => [
            'name' => "required|max:255"
        ],
        'job_type' => [
            'id' => "required|numeric|min:1|max:5",
            'name' => "required|max:50",
        ]
    ];

    public function getAllVacancies(Request $request)
    {
        try {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE) {
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            // checking for correct access role
            $user = User::where("id", "=", $content->user_id)->first();
            if($user['user_type_id'] !== 1) {
                return response()->json(["statue" => "error", "message" => "Only employer can create vacancies."], 400);
            }

            // fetching data
            $data = array();
            $company_id = $content->company_id;
            $job_posts = JobPost::where('company_id', '=', $company_id)->get();

            foreach ($job_posts as $job_post)
            {
                $job_post_id = $job_post['id'];
                $location_id = $job_post['location_id'];
                $work_experience_type = $job_post['work_experience_type'];

                $job_types = JobPostJobType::where('job_post_id', '=', $job_post_id)
                    ->join('job_type', 'job_post_job_type.job_type_id', '=', 'job_type.id')
                    ->select('job_type.*')
                    ->get();
                $skills = JobPostSkills::where('job_post_id', '=', $job_post_id)
                    ->join('skills', 'job_post_skills.skill_id', '=', 'skills.id')
                    ->select('skills.*')
                    ->get();
                $location = JobLocation::where('job_location.id', '=', $location_id)->first();
                $country = Countries::where('id', '=', $location['country_id'])->select('id', 'name')->first();
                $state = States::where('id', '=', $location['state_id'])->select('id', 'name')->first();
                $city = Cities::where('id', '=', $location['city_id'])->select('id', 'name')->first();
                $work_experience = WorkExperience::where('id', '=', $work_experience_type)->first();

                array_push($data,
                    [
                        'job_post' => $job_post,
                        'job_type_array' => $job_types,
                        'job_location_details' => (object) [
                            'country' => $country,
                            'state' => $state,
                            'city' => $city,
                            'address' => $location['address']
                        ],
                        'job_location' => $location,
                        'skill_array' => $skills,
                        'work_experience_type' => $work_experience
                    ]
                );
            }

            return $data;
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function getJobTypeArray(): array
    {
        return (new \App\Models\JobType)->getJobTypeArray();
    }

    public function getWorkExperienceArray(): array
    {
        return WorkExperience::all()->toArray();
    }

    public function createJobPost(Request $request)
    {
        try {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE) {
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            $job_post = $content->job_post;
            $job_post_main = $job_post->job_post;
            $job_type_array = $job_post->job_type_array;
            $job_location = $job_post->job_location;
            $skill_array = $job_post->skill_array;
            $user_id = $content->user_id;

            $user = User::where("id", "=", $user_id)->first();
            if($user['user_type_id'] !== 1) {
                return response()->json(["statue" => "error", "message" => "Only employer can create vacancies."], 400);
            }

            //checking if the data passed satisfies the validation requirements
            $validator = Validator::make((array)$job_location, $this->rules['job_location']);
            if($validator->fails()){
                return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
            }
            $location_id = JobLocation::insertGetId((array)$job_location);


            $job_post_main->location_id = $location_id;
            $validator = Validator::make((array)$job_post_main, $this->rules['job_post']);
            if($validator->fails()){
                return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
            }
            $job_post_id = JobPost::insertGetId((array)$job_post_main);


            foreach ($job_type_array as $job_type)
            {
                $validator = Validator::make((array)$job_type, $this->rules['job_type']);
                if($validator->fails()){
                    return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
                }
                JobPostJobType::insert(['job_post_id' => $job_post_id, 'job_type_id' => $job_type->id]);
            }


            $new_skills = array();
            $job_post_skills= array();
            foreach ($skill_array as $skillKey => $skillVal)
            {
                if (gettype($skillVal) == 'string')
                {
                    array_push($new_skills, ['name' => $skillVal]);
                    unset($skill_array[$skillKey]);
                }
                else
                {
                    array_push($job_post_skills, ['skill_id' => $skillVal->id, 'job_post_id' => $job_post_id]);
                }
            }
            if (count($new_skills) > 0)
            {
                foreach ($new_skills as $new_skill)
                {
                    $validator = Validator::make($new_skill, $this->rules['skills']);
                    if ($validator->fails())
                    {
                        return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                    }
                }

                $new_skills_id = (new SkillsController)->insertSkills($new_skills);
                foreach ($new_skills_id as $val) {
                    array_push($job_post_skills, ['skill_id' => $val, 'job_post_id' => $job_post_id]);
                }
            }
            JobPostSkills::insert($job_post_skills);

            return response()->json(["status" => "success", "message" => "Вакансия успешно создана"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function updateJobPost(Request $request)
    {
        try {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE) {
                return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
            }

            $job_post = $content->job_post;
            $job_post_main = $job_post->job_post;
            $job_type_array = $job_post->job_type_array;
            $job_location = $job_post->job_location;
            $skill_array = $job_post->skill_array;
            $user_id = $content->user_id;

            $user = User::where("id", "=", $user_id)->first();
            if($user['user_type_id'] !== 1) {
                return response()->json(["statue" => "error", "message" => "Only employer can create vacancies."], 400);
            }

            //checking if the data passed satisfies the validation requirements
            $validator = Validator::make((array)$job_location, $this->rules['job_location']);
            if($validator->fails()){
                return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
            }
            JobLocation::where('id', '=', $job_location->id)->update((array)$job_location);


            $job_post_id = $job_post_main->id;
            $validator = Validator::make((array)$job_post_main, $this->rules['job_post']);
            if($validator->fails()){
                return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
            }
            JobPost::where('id', '=', $job_post_id)->update((array)$job_post_main);


            JobPostJobType::where('job_post_id', '=', $job_post_id)->delete();
            foreach ($job_type_array as $job_type)
            {
                $validator = Validator::make((array)$job_type, $this->rules['job_type']);
                if($validator->fails()){
                    return response()->json(["status" => "error", "message" => "Ошибки валидации", "errors" => $validator->errors()], 400);
                }
                JobPostJobType::insert(['job_post_id' => $job_post_id, 'job_type_id' => $job_type->id]);
            }


            JobPostSkills::where('job_post_id', '=', $job_post_id)->delete();
            $new_skills = array();
            $job_post_skills= array();
            foreach ($skill_array as $skillKey => $skillVal)
            {
                if (gettype($skillVal) == 'string')
                {
                    array_push($new_skills, ['name' => $skillVal]);
                    unset($skill_array[$skillKey]);
                }
                else
                {
                    array_push($job_post_skills, ['skill_id' => $skillVal->id, 'job_post_id' => $job_post_id]);
                }
            }
            if (count($new_skills) > 0)
            {
                foreach ($new_skills as $new_skill)
                {
                    $validator = Validator::make($new_skill, $this->rules['skills']);
                    if ($validator->fails())
                    {
                        return response()->json(["status" => "error", "errors" => $validator->errors()], 400);
                    }
                }

                $new_skills_id = (new SkillsController)->insertSkills($new_skills);
                foreach ($new_skills_id as $val) {
                    array_push($job_post_skills, ['skill_id' => $val, 'job_post_id' => $job_post_id]);
                }
            }
            JobPostSkills::insert($job_post_skills);

            return response()->json(["status" => "success", "message" => "Вакансия успешно создана"], 201);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }

    public function deleteJobPost(Request $request)
    {
        try
        {
            //checking if the request body is filled correctly
            $content = json_decode($request->getContent());
            if (json_last_error() != JSON_ERROR_NONE)
            {
                return response()->json(["status" => "error", "message" => "JSON validation error"], 400);
            }

            JobPost::find($content->job_post_id)->delete();

            return response()->json(["status" => "success", "message" => "CV successfully deleted"], 200);
        }
        catch (\Exception $e) {
            return response()->json(["status" => "error", "message" =>  $e->getMessage(). " " . $e->getFile() . " LINE:" . $e->getLine()], 400);
        }
    }
}
