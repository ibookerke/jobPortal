<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:2|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "error", "message" => "Форма заполнена с ошибками", "error_data" => $validator->errors()], 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['status' => 'error', "message" => "Неверное имя пользователя или пароль"], 401);
        }

        return response()->json(["status" => "success", "data" => $this->createNewToken($token)], 200);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {

        //checking if the request body is filled correctly
        $content = json_decode($request->getContent());
        if(json_last_error() != JSON_ERROR_NONE){
            return response()->json(["status" => "error", "message" => "Ошибка валидации JSON"], 400);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'user_type_id' => 'required|max:1|min:1'
        ]);

        if($content->user_type_id != 1 && $content->user_type_id != 2) {
            return response()->json(["status" => "error", "message" => "Некорректный тип пользовталя"], 400);
        }

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'Пользователь успешно создан',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout() {
        try{
            auth()->logout();

            return response()->json(['message' => 'User successfully signed out']);
        }
        catch(\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        try{
            return response()->json(auth()->user(), 200);
        }
        catch(\Exception $e){
            return response()->json(["status" => "error", "message" => $e->getMessage()."<br>".$e->getFile()." LINE:".$e->getLine()], 500);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
