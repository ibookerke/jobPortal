<?php

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
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group(['middleware' => ['jwt.verify'], ], function() {
    //making locations
    Route::post("/getLocations", [\App\Http\Controllers\LocationController::class, "getLocations"]);
    Route::post("/saveLocation", [\App\Http\Controllers\LocationController::class, "saveLocation"]);

    Route::post("/createEvent", [\App\Http\Controllers\EventController::class, "create_event"]);
});

Route::get("/get_cities", [\App\Http\Controllers\Controller::class, "get_cities"]);
