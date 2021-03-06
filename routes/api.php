<?php

use App\Http\Controllers\CVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
});

Route::group(['middleware' => ['jwt.verify'], ], function() {
    //auth
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //making locations
    Route::post("/createCompany", [\App\Http\Controllers\CompanyController::class, "createCompany"]);
    Route::post("/updateCompany", [\App\Http\Controllers\CompanyController::class, "updateCompany"]);
    Route::get("/getCompany/{user_id}", [\App\Http\Controllers\CompanyController::class, "getCompany"]);

    Route::post("/search_business_stream", [\App\Http\Controllers\BusinessStreamController::class, "search_business_stream"]);
    Route::post("/get_business_stream", [\App\Http\Controllers\BusinessStreamController::class, "get_business_stream"]);

    Route::post("/getLocations", [\App\Http\Controllers\LocationController::class, "getLocations"]);
    Route::post("/saveLocation", [\App\Http\Controllers\LocationController::class, "saveLocation"]);

    Route::post('/get_seeker_cvs', [CVController::class, 'getSeekerCVs']);
    Route::post('/get_seeker_cvs_for_respond', [CVController::class, 'getSeekerCVsForRespond']);
    Route::post('/save_seeker_cv', [CVController::class, 'createCV']);
    Route::post('/update_seeker_cv', [CVController::class, 'updateCV']);
    Route::post('/delete_seeker_cv', [CVController::class, 'deleteCV']);
    Route::post('/seeker_respond_to_job_post', [CVController::class, 'respondToJobPost']);

    Route::post('/get_all_vacancies', [\App\Http\Controllers\JobPostController::class, 'getAllCompanyJobPosts']);
    Route::post('/get_job_type_array', [\App\Http\Controllers\JobPostController::class, 'getJobTypeArray']);
    Route::post('/get_work_experience_array', [\App\Http\Controllers\JobPostController::class, 'getWorkExperienceArray']);
    Route::post('/create_job_post', [\App\Http\Controllers\JobPostController::class, 'createJobPost']);
    Route::post('/update_job_post', [\App\Http\Controllers\JobPostController::class, 'updateJobPost']);
    Route::post('/delete_job_post', [\App\Http\Controllers\JobPostController::class, 'deleteJobPost']);

    Route::post('/get_main_page_filters', [\App\Http\Controllers\JobPostController::class, 'mainPageFilters']);
    Route::post('/fetch_job_posts', [\App\Http\Controllers\JobPostController::class, 'fetchJobPosts']);

    Route::post('/fetch_responded_to_job_posts', [\App\Http\Controllers\JobPostController::class, 'fetchResondedToJobPosts']);

    Route::post('/get_countries', [\App\Http\Controllers\CountriesController::class, 'getCountries']);
    Route::post('/get_states_by_country', [\App\Http\Controllers\StatesController::class, 'getStatesByCountry']);
    Route::post('/get_cities_by_state', [\App\Http\Controllers\CitiesController::class, 'getCitiesByState']);

    Route::post('/get_skills', [\App\Http\Controllers\SkillsController::class, 'getSkills']);
    Route::post('/search_skills', [\App\Http\Controllers\SkillsController::class, 'searchSkills']);

    Route::post("/createEvent", [\App\Http\Controllers\EventController::class, "create_event"]);

    Route::post("/uploadAvatar", [\App\Http\Controllers\CompanyController::class, "uploadAvatar"]);

    Route::post("/getCVs", [CVController::class, "getCvs"]);

});

Route::get("/get_cities", [\App\Http\Controllers\Controller::class, "get_cities"]);
