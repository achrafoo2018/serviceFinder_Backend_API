<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Provider;
use Auth;
use JWTAuth;
use App\Http\Controllers\Api\UserController;

class AuthController extends Controller
{
    public function login(Request $request){
        $creds = $request->only(['email', 'password']);
        $token=JWTAuth::attempt($creds);

        if(!$token){
            return response()->json([
                'success'=>false,
                'message' => 'invalid credentials'
            ]);
        }
        $user = Auth::user();
        $user->remember_token = $token;
        $user->save();
        if($user->type == 'Provider')
            return response()->json([
                'success'=>true,
                'token' => $token,
                'user' => $user,
                'provider' => Provider::where("provider_id", $user->id)->first()
            ]);

        return response()->json([
            'success'=>true,
            'token' => $token,
            'user' => $user
        ]);
    }



    public function register(Request $request){
        $rules = [
            'type' => 'required',
            'email'    => 'unique:users|required',
            'password' => 'required',
        ];

        $input     = $request->only('email','password', "type");
        $validator = Validator::make($input, $rules);
        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'type' => $request['type']
        ]);

        if(strtolower($request['type']) == "provider"){
            Provider::create([
                "provider_id" => $user->id
            ]);
        }

        return $this->login($request);

    }
    public function logout(Request $request){
        try{

            $user = User::where('remember_token', $request->bearerToken())->first();
            if($user){

                    $user->remember_token = "";
                    $user->save();

                    return response()->json([
                        'success' => true,
                        'message' => "User logged out successfully!",
                    ]);

            }
            return UserController::getUserByIdError();
        }
        catch(Exception $e)
        {
            return response()->json([
                'message' => "".$e
            ]);
        }

        }

}

